<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = $_SESSION['nomUsuario'];

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {


            $('#customRadio1').change(function () {
                enabledOptionFechas();
            });

            $('#customRadio2').change(function () {
                disableOptionFechas();
            });

            function enabledOptionFechas() {
                var fechaInicio = document.getElementById('fechaInicio');
                var fechaTermino = document.getElementById('fechaTermino');

                fechaInicio.disabled = false;
                fechaTermino.disabled = false;
            };

            function disableOptionFechas() {
                var fechaInicio = document.getElementById('fechaInicio');
                var fechaTermino = document.getElementById('fechaTermino');

                fechaInicio.disabled = true;
                fechaTermino.disabled = true;
            };
        });
    </script>
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script>
        function mostrarInputs1(radio) {
            var panolDiv = document.getElementById('rango');
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

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <?php

                                function mostrarMensaje($tipo, $titulo, $texto)
                                {
                                    echo
                                    <<<EOL
                                        <script>
                                            Swal.fire({
                                                icon: '$tipo',
                                                title: '$titulo',
                                                text: '$texto'
                                            });
                                        </script>
                                EOL;
                                }

                                $mensajes = [
                                    'exitoAgregarCategoria' => ['success', 'Éxito', 'Categoría creada con éxito.'],
                                    'errorAgregarCategoria' => ['error', 'Error', 'Error al agregar categoría. Reintente.'],
                                    'existeCategoria' => ['warning', 'Advertencia', 'La categoría ya se encuentra registrada. Reintente.'],

                                    'exitoAgregarObservacion' => ['success', 'Éxito', 'Observación creada con éxito.'],
                                    'errorAgregarObservacion' => ['error', 'Error', 'Error al agregar observación. Reintente.'],

                                    'exitoModificarObservacion' => ['success', 'Éxito', 'Observación modificada con éxito.'],
                                    'errorModificarObservacion' => ['error', 'Error', 'Error al modificar observación. Reintente.'],

                                    'exitoEliminarObservacion' => ['success', 'Éxito', 'Observación eliminada con éxito.'],
                                    'errorEliminarObservacion' => ['error', 'Error', 'Error al eliminar observación. Reintente.'],

                                    'errorDescargarBitacora' => ['error', 'Error', 'Error al descargar bitácora. Reintente.'],
                                    'bitacoraSinEventos' => ['error', 'Error', 'No existen eventos en el periodo consultado.'],
                                    'errorFechaTermino' => ['error', 'Error', 'La fecha de término no debe ser antes que la fecha de inicio. Reintente.'],

                                ];

                                foreach ($mensajes as $param => $mensaje) {
                                    if (isset($_GET[$param])) {
                                        mostrarMensaje(...$mensaje);
                                    }
                                }
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
                                                        <input type="hidden" name="tecnicoBitacora" id="tecnicoBitacora" value="<?php echo $idUsuario; ?>">
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
                                <form method="post" action="../sdo-funciones/bitacora-descargar.php" target="_blank">
                                    <button type="button" style="float: right;" data-toggle="modal" data-target="#modalDownloadBitacora" id="inputMozi" class="btn btn-info"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Bitacora</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalDownloadBitacora" name="modalDownloadBitacora" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Descargar Bitacora</h5>

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="selectCategoriaDescarga">Seleccione Categoria:&nbsp;</label><code>Dejar en 'Seleccione' si quiere filtrar por todos los registros.</code>
                                                        <select class="form-control" id="selectCategoriaDescarga" name="selectCategoriaDescarga">
                                                            <option value="Todos" class="text-bold">Seleccione...</option>
                                                            <?php

                                                            $obtenerIDBitacora2 = obtenerIDBitacora();

                                                            $queryCatDesc = "SELECT * FROM subcategorias WHERE idCategoria = " . $obtenerIDBitacora2 . " ORDER BY nomSubcategoria ASC";

                                                            $resultCatDesc = mysqli_query($conexion, $queryCatDesc);

                                                            // Verifica si hay resultados
                                                            if ($resultCatDesc->num_rows > 0) {
                                                                $options = array();
                                                                while ($rowCatDesc = $resultCatDesc->fetch_assoc()) {
                                                                    ?>
                                                                    <option value="<?php echo $rowCatDesc['idSubcategoria'] ?>"><?php echo $rowCatDesc['nomSubcategoria'] ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <hr>
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
                                <input type="hidden" name="idBitacoraAjax" id="idBitacoraAjax" value="<?php echo $idCategoria; ?>">


                                <div class="table-responsive">
                                    <table id="miTabla" class="table table-bordered">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>Fecha</center>
                                            </th>
                                            <th>
                                                <center>Categoria</center>
                                            </th>
                                            <th>
                                                <center>Tecnico</center>
                                            </th>
                                            <th>
                                                <center>Visualizar</center>
                                            </th>
                                            <th>
                                                <center>Modificar</center>
                                            </th>
                                            <th>
                                                <center>Eliminar</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>Fecha
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Categoria</center>
                                            </th>
                                            <th>
                                                <center>Tecnico</center>
                                            </th>
                                            <th>
                                                <center>Visualizar</center>
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

                                    <div class="modal" id="modalObservacion" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered modal-md text-justify" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title">Visualizando Registro de Bitácora</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        <strong>Técnico Bitácora: </strong>
                                                        <span id="modalTecnicoBitacora"></span>
                                                    </p>
                                                    <p>
                                                        <strong>Fecha Bitácora: </strong>
                                                        <span id="modalFechaBitacora"></span>
                                                    </p>
                                                    <p>
                                                        <strong>Observación Bitácora:</strong>
                                                        <br>
                                                        <br>
                                                        <span id="modalObservacionBitacora"></span>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-md text-justify" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="modalEditarLabel">Editar Bitácora</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="formEditarBitacora" method="post" action="../sdo-funciones/admin-modificarBitacora.php">
                                                    <div class="modal-body">
                                                        <!-- Formulario de Edición -->
                                                        <input type="hidden" id="modalEditarIdBitacora" name="idBitacora" value="">

                                                        <div class="form-group col-12">
                                                            <label for="idSubCateg" style="font-size: 12px;">Categoria: <code>*</code></label>
                                                            <select class="form-control" required name="subcategoriaModificar" id="idSubCateg">
                                                                <option value="Seleccione" disabled>Seleccione...</option>
                                                                <?php

                                                                $obtenerIDBitacora = obtenerIDBitacora();

                                                                $query = "SELECT * FROM subcategorias WHERE idCategoria = " . $obtenerIDBitacora . " ORDER BY nomSubcategoria ASC";

                                                                $result = mysqli_query($conexion, $query);

                                                                // Verifica si hay resultados
                                                                if ($result->num_rows > 0) {
                                                                    $options = array();
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        ?>
                                                                        <option value="<?php echo $row['idSubcategoria'] ?>"><?php echo $row['nomSubcategoria'] ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="modalEditarObservacionBitacora">Observación</label>
                                                            <textarea style="resize: none" class="form-control" id="modalEditarObservacionBitacora" name="modObservacionBitacora" rows="8" required></textarea>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Guardar</button>

                                                        <!-- Cancelar Button -->
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

                                                        <!-- Save or Submit Button -->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
        <script>
            var nomUsuario = <?php echo json_encode($nomUsuario); ?>;
            nomUsuario = nomUsuario.toUpperCase(); // Convertir a mayúsculas

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
                        "url": "ajax-listado-bitacora.php", // Archivo PHP que contiene la consulta SQL
                        "type": "POST",
                        "data": function (d) {
                            d.idBitacoraAjax = $('#idBitacoraAjax').val(); // Pasa el valor seleccionado del select como parámetro


                        },
                        "dataSrc": ""
                    },
                    "columns": [

                        {"data": "fechaBitacora"},
                        {"data": "nomSubCategoria"},
                        {"data": "tecnicoBitacora"},
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                var buttonHTML;

                                if (row.idBitacora > 0) {
                                    buttonHTML = '<button class="btn btn-primary btn-ver btn-block" data-id-bitacora="' + row.idBitacora + '" data-tecnico-bitacora="' + row.tecnicoBitacora + '" data-fecha-bitacora="' + row.fechaBitacora + '" data-observacion-bitacora="' + row.observacionBitacora + '"><i class="fas fa-eye"></i></button>';
                                } else {
                                    buttonHTML = '<button class="btn btn-primary btn-block" data-id="' + row.idBitacora + '" disabled><i class="fas fa-eye"></i></button>';
                                }

                                return buttonHTML;
                            }
                        },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                var buttonHTML;

                                if (row.idBitacora > 0) {
                                    buttonHTML = '<button class="btn btn-warning btn-editar btn-block" data-id-bitacora="' + row.idBitacora + '" data-id-subcategoria="' + row.idSubcategoria + '" data-tecnico-bitacora="' + row.tecnicoBitacora + '" data-fecha-bitacora="' + row.fechaBitacora + '" data-observacion-bitacora="' + row.observacionBitacora + '"><i class="fas fa-pen"></i></button>';
                                } else {
                                    buttonHTML = '<button class="btn btn-warning btn-block" data-id="' + row.idBitacora + '" disabled><i class="fas fa-pen"></i></button>';
                                }

                                return buttonHTML;
                            }
                        },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                var buttonHTML;

                                if (row.idBitacora > 0) {
                                    buttonHTML = '<button class="btn btn-danger btn-block btn-eliminar" data-id-bitacora="' + row.idBitacora + '" ><i class="fas fa-times"></i></button>';
                                } else {
                                    buttonHTML = '<button class="btn btn-danger btn-block" data-id="' + row.idBitacora + '" disabled><i class="fas fa-times"></i></button>';
                                }

                                return buttonHTML;
                            }
                        },


                        // Agrega más columnas según sea necesario
                    ],
                    "createdRow": function (row, data, dataIndex) {
                        $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                        $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                    },
                    initComplete: function () {
                        this.api().columns([1]).every(function () {
                            var column = this;
                            var select = $('<select style="width: 100%;"><option value="">Categoria...</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        });
                    }
                });
            });
            $(document).on('click', '.btn-ver', function () {
                var tecnicoBitacora = $(this).data('tecnico-bitacora');
                var fechaBitacora = $(this).data('fecha-bitacora');
                var observacionBitacora = $(this).data('observacion-bitacora');

                // Actualizar el contenido del modal con los detalles
                document.getElementById('modalTecnicoBitacora').innerText = tecnicoBitacora;
                document.getElementById('modalFechaBitacora').innerText = fechaBitacora;
                document.getElementById('modalObservacionBitacora').innerText = observacionBitacora;


                // Mostrar el modal
                $('#modalObservacion').modal('show');
            });

            $(document).on('click', '.btn-editar', function () {
                // Obtén los datos de los atributos data-
                var idBitacora = $(this).data('id-bitacora');
                var tecnicoBitacora = $(this).data('tecnico-bitacora');
                var fechaBitacora = $(this).data('fecha-bitacora');
                var observacionBitacora = $(this).data('observacion-bitacora');
                var idSubcategoria = $(this).data('id-subcategoria');


                // Asigna los valores a los elementos del modal de edición
                $('#modalEditarIdBitacora').val(idBitacora);
                $('#modalEditarObservacionBitacora').val(observacionBitacora);

                // Itera sobre las opciones del <select>
                $('#idSubCateg option').each(function () {
                    var optionValue = $(this).val();

                    // Activa la opción correspondiente y desactiva las demás
                    if (optionValue == idSubcategoria) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });


                // Abre el modal de edición
                $('#modalEditar').modal('show');
            });

            $(document).on('click', '.btn-eliminar', function (e) {
                e.preventDefault();
                var idBitacora = $(this).data('id-bitacora');
                var dataString = 'idBitacora=' + idBitacora;
                console.log(dataString);
                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar Registro de Bitacora?',
                    text: 'Esta acción no se puede revertir',
                    showConfirmButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No, lo pensaré',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/bitacora-eliminar.php',
                            type: 'POST',
                            data: dataString,
                            success: function (response) {
                                console.log('Eliminado... ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro de Bitacora Eliminado',
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
            });
        </script>
        <script>
            $(document).ready(function () {
                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#displayTableExample tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
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
<!-- Page specific script -->

<script>
    $(function () {
        $('#example2').DataTable({
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
            },
            initComplete: function () {
                this.api().columns([1]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Categoria...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>
</body>

</html>
