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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
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
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- TITULO INTERFAZ -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Bitacora</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                    <!-- FIN TITULO INTERFAZ -->

                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body text-center">
                                <div class="row justify-content-center">
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalAgregarCategoria">
                                            <i class="fas fa-plus"></i> Agregar Categoria
                                        </button>
                                        <div class="modal fade" id="modalAgregarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalAgregarCategoriaLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="modalAgregarCategoriaLabel">Nueva Categoría</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form id="formAgregarCategoria">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="nomSubcategoria">Nombre de Categoría</label>
                                                                <input type="text" class="form-control" id="nomSubcategoria" name="nomSubcategoria" required placeholder="Ej: Control de Acceso">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalAgregarBitacora">
                                            <i class="fas fa-plus"></i> Agregar Observación
                                        </button>
                                        <div class="modal fade" id="modalAgregarBitacora" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title" id="modalAgregarLabel">Nueva Observación</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form id="formAgregarBitacora">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="tecnicoBitacora" value="<?php echo htmlspecialchars($_SESSION['nomUsuario']); ?>">
                                                            <div class="form-group">
                                                                <label for="add-categoria">Categoría</label>
                                                                <select class="form-control" id="add-categoria" name="idSubcategoria" required style="width: 100%"></select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="add-observacion">Observación</label>
                                                                <textarea class="form-control" id="add-observacion" name="observacionBitacora" rows="6" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Guardar Observación</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body text-center">
                                <table id="tableBitacora" class="table table-bordered table-striped text-center table-sm">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Categoria</th>
                                        <th>Personal</th>
                                        <th>Visualizar</th>
                                        <th>Modificar</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Categoria</th>
                                        <th>Personal</th>
                                        <th>Visualizar</th>
                                        <th>Modificar</th>
                                    </tr>
                                    </tfoot>
                                </table>

                                <!-- Modal Visualizar -->
                                <div class="modal fade" id="modalVisualizarBitacora" tabindex="-1" role="dialog" aria-labelledby="modalVisualizarLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="modalVisualizarLabel">Detalle de Observación</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-justify">
                                                <p><strong>Fecha:</strong> <span id="vis-fecha"></span></p>
                                                <p><strong>Categoría:</strong> <span id="vis-categoria"></span></p>
                                                <p><strong>Personal:</strong> <span id="vis-tecnico"></span></p>
                                                <hr>
                                                <p><strong>Observación:</strong></p>
                                                <div class="border p-3 rounded bg-light text-justify" id="vis-observacion"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Modificar -->
                                <div class="modal fade" id="modalModificarBitacora" tabindex="-1" role="dialog" aria-labelledby="modalModificarLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title" id="modalModificarLabel">Modificar Observación</h5>
                                                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="formModificarBitacora">
                                                <div class="modal-body text-justify">
                                                    <input type="hidden" id="mod-idBitacora" name="idBitacora">
                                                    <div class="form-group">
                                                        <label for="mod-fecha">Fecha</label>
                                                        <input type="text" class="form-control" id="mod-fecha" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="mod-categoria">Categoría</label>
                                                        <select class="form-control text-left" id="mod-categoria" name="categoria" required style="width: 100%"></select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="mod-observacion">Observación</label>
                                                        <textarea class="form-control" id="mod-observacion" name="observacion" rows="6" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-warning"><i class="fas fa-upload"></i>&nbsp;&nbsp;Guardar Cambios</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <script>
                                    const nomUsuario = <?php echo json_encode($_SESSION['nomUsuario']); ?>;

                                    //PARA AGREGAR CATEGORIAS
                                    $('#formAgregarCategoria').on('submit', function (e) {
                                        e.preventDefault();

                                        const $btn = $(this).find('button[type="submit"]');
                                        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                                        $.ajax({
                                            url: '/sdo-funciones/bitacora_agregar_categoria.php',
                                            method: 'POST',
                                            data: $(this).serialize(),
                                            success: function (response) {
                                                if (response.success) {
                                                    toastr.success('Categoría agregada correctamente');
                                                    $('#modalAgregarCategoria').modal('hide');
                                                    $('#formAgregarCategoria')[0].reset();

                                                    // Opcional: recargar select en algún formulario si lo tienes visible
                                                    cargarSubcategoriasAdd?.();
                                                    cargarSubcategoriasSeleccionadas?.();
                                                } else {
                                                    toastr.error(response.error || 'Error desconocido');
                                                }
                                            },
                                            error: function (xhr) {
                                                console.error(xhr.responseText);
                                                toastr.error('No se pudo guardar la categoría');
                                            },
                                            complete: function () {
                                                $btn.prop('disabled', false).html('Guardar');
                                            }
                                        });
                                    });
                                    //FIN PARA AGREGAR CATEGORIAS


                                    //PARA AGREGAR OBSERVACIONES
                                    function cargarSubcategoriasAdd() {
                                        let $select = $('#add-categoria');

                                        // Limpia el contenido anterior y destruye Select2 si ya está instanciado
                                        if ($select.hasClass('select2-hidden-accessible')) {
                                            $select.select2('destroy');
                                        }

                                        $select.empty(); // limpia por si acaso

                                        $.ajax({
                                            url: '/sdo-includes/bitacora_subcategorias_cargar.php',
                                            type: 'GET',
                                            dataType: 'json',
                                            success: function (subcategorias) {
                                                // console.log("Subcategorías recibidas:", subcategorias);

                                                if (subcategorias.length === 0) {
                                                    $select.append('<option value="">No hay categorías</option>');
                                                } else {
                                                    subcategorias.forEach(function (sub) {
                                                        $select.append(`<option value="${sub.idSubcategoria}">${sub.nomSubcategoria}</option>`);
                                                    });
                                                }
                                                // console.log("HTML del select antes de activar Select2:", $select.html());



                                                $select.select2({
                                                    dropdownParent: $('#modalAgregarBitacora'),
                                                    theme: 'bootstrap4',
                                                    width: '100%',
                                                    placeholder: 'Seleccione una categoría'
                                                });
                                            },
                                            error: function (xhr) {
                                                console.error('Error al cargar subcategorías:', xhr.responseText);
                                                $select.html('<option value="">Error al cargar</option>');
                                            }
                                        });
                                    }


                                    // Cargar subcategorías al abrir modal
                                    $(function () {
                                        $('#modalAgregarBitacora').on('shown.bs.modal', function () {
                                            console.log('Modal abierto: cargando subcategorias');

                                            $('#add-observacion').val('');
                                            cargarSubcategoriasAdd(); // <- aquí llama tu función de carga
                                        });
                                    });

                                    // Enviar nuevo registro
                                    $('#formAgregarBitacora').on('submit', function (e) {
                                        e.preventDefault();
                                        let $btn = $(this).find('button[type="submit"]');
                                        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                                        $.ajax({
                                            url: '/sdo-funciones/bitacora_agregar_observacion.php',
                                            method: 'POST',
                                            data: $(this).serialize(),
                                            success: function (response) {
                                                $('#modalAgregarBitacora').modal('hide');
                                                $('#tableBitacora').DataTable().ajax.reload(null, false);
                                                toastr.success('Observación agregada correctamente');
                                            },
                                            error: function (xhr) {
                                                console.error('Error AJAX:', xhr.responseText);
                                                toastr.error('No se pudo agregar la observación');
                                            },
                                            complete: function () {
                                                $btn.prop('disabled', false).html('Guardar Observación');
                                            }
                                        });
                                    });
                                    //FIN PARA AGREGAR OBSERVACIONES

                                    //PARA MOSTRAR OBSERVACIONES
                                    $(document).ready(function () {
                                        // Toastr config
                                        toastr.options = {
                                            "closeButton": true,
                                            "progressBar": true,
                                            "positionClass": "toast-top-right",
                                            "timeOut": "3000"
                                        };

                                        // Init DataTable
                                        var tableBitacora = $('#tableBitacora').DataTable({
                                            lengthChange: false,
                                            searching: true,
                                            autoWidth: false,
                                            responsive: true,
                                            ordering: false,
                                            language: {
                                                lengthMenu: "Visualizando _MENU_ Registros por Página",
                                                zeroRecords: "No se han encontrado Registros.",
                                                info: "Mostrando página _PAGE_ de _PAGES_",
                                                infoEmpty: "No existen Registros.",
                                                infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                                                search: "Buscar:",
                                                processing: "Procesando...",
                                                paginate: {
                                                    previous: "Anterior",
                                                    next: "Siguiente"
                                                }
                                            },
                                            ajax: {
                                                url: "/sdo-includes/bitacora_cargar_datos.php",
                                                type: "POST",
                                                dataSrc: "data"
                                            },
                                            columns: [
                                                { data: "fechaCompleta" },
                                                { data: "nomSubcategoria" },
                                                { data: "tecnicoBitacora" },
                                                {
                                                    data: "idBitacora",
                                                    render: function(data) {
                                                        return `<button class="btn btn-block btn-primary visualizar-bitacora" data-id="${data}">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>`;
                                                    }
                                                },
                                                {
                                                    data: "idBitacora",
                                                    render: function(data, type, row) {
                                                        return (row.tecnicoBitacora === nomUsuario)
                                                            ? `<button class="btn btn-block btn-warning modificar-bitacora" data-id="${data}">
                                                                    <i class="fas fa-edit"></i>
                                                               </button>`
                                                            : 'Deshabilitado';
                                                    }
                                                }
                                            ],
                                            createdRow: function (row, data, dataIndex) {
                                                // console.log(data);
                                                $('td', row).css('vertical-align', 'middle');
                                            }
                                        });

                                        // Visualizar Modal
                                        $(document).on('click', '.visualizar-bitacora', function () {
                                            const id = $(this).data('id');
                                            $.getJSON('/sdo-includes/bitacora_cargar_datos_modal.php', { idBitacora: id }, function (data) {
                                                $('#vis-fecha').text(data.fechaCompleta);
                                                $('#vis-categoria').text(data.nomSubcategoria);
                                                $('#vis-tecnico').text(data.tecnicoBitacora);
                                                $('#vis-observacion').html(data.observacionBitacora);
                                                $('#modalVisualizarBitacora').modal('show');
                                            }).fail(function () {
                                                toastr.error('No se pudo cargar la bitácora');
                                            });
                                        });

                                        // Modificar Modal
                                        $(document).on('click', '.modificar-bitacora', function () {
                                            const id = $(this).data('id');
                                            $.getJSON('/sdo-includes/bitacora_cargar_datos_modal.php', { idBitacora: id }, function (data) {
                                                $('#mod-idBitacora').val(data.idBitacora);
                                                $('#mod-fecha').val(formatearFechaDMYHI(data.fechaBitacora));
                                                $('#mod-tecnico').val(data.tecnicoBitacora);
                                                $('#mod-observacion').val(data.observacionBitacora);
                                                cargarSubcategoriasSeleccionadas(data.idSubcategoria);
                                                $('#modalModificarBitacora').modal('show');
                                            }).fail(function () {
                                                toastr.error('No se pudo cargar la bitácora');
                                            });
                                        });

                                        // Cargar Subcategorías con Select2
                                        function cargarSubcategoriasSeleccionadas(idSeleccionada = null) {
                                            $.ajax({
                                                url: '/sdo-includes/bitacora_subcategorias_cargar.php',
                                                type: 'GET',
                                                dataType: 'json',
                                                success: function (subcategorias) {
                                                    let $select = $('#mod-categoria');
                                                    $select.empty();
                                                    subcategorias.forEach(function (sub) {
                                                        let selected = (idSeleccionada == sub.idSubcategoria) ? 'selected' : '';
                                                        $select.append(`<option style="text-align: justify;" value="${sub.idSubcategoria}" ${selected}>${sub.nomSubcategoria}</option>`);
                                                    });
                                                    $select.select2({
                                                        dropdownParent: $('#modalModificarBitacora'),
                                                        width: '100%',
                                                        theme: 'bootstrap4',
                                                        placeholder: 'Seleccione una categoría',
                                                        templateResult: function (data) {
                                                            if (!data.id) return data.text;
                                                            return $('<span style="text-align: justify; display: block;">' + data.text + '</span>');
                                                        },
                                                        templateSelection: function (data) {
                                                            return $('<span style="text-align: left; display: block;">' + data.text + '</span>');
                                                        }
                                                    });
                                                },
                                                error: function () {
                                                    $('#mod-categoria').html('<option style="text-align: justify;" value="">Error al cargar</option>');
                                                }
                                            });
                                        }

                                        function formatearFechaDMYHI(fechaString) {
                                            const fecha = new Date(fechaString);
                                            const d = String(fecha.getDate()).padStart(2, '0');
                                            const m = String(fecha.getMonth() + 1).padStart(2, '0');
                                            const y = fecha.getFullYear();
                                            const h = String(fecha.getHours()).padStart(2, '0');
                                            const i = String(fecha.getMinutes()).padStart(2, '0');
                                            return `${d}-${m}-${y} ${h}:${i}`;
                                        }

                                        // Guardar Cambios - Submit Modificar
                                        $('#formModificarBitacora').on('submit', function (e) {
                                            e.preventDefault();
                                            let $btn = $(this).find('button[type="submit"]');
                                            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando');

                                            $.ajax({
                                                url: '/sdo-funciones/bitacora_modificar_guardar.php',
                                                method: 'POST',
                                                data: $(this).serialize(),
                                                success: function (response) {
                                                    $('#modalModificarBitacora').modal('hide');
                                                    tableBitacora.ajax.reload(null, false);
                                                    toastr.success('Observación actualizada correctamente');
                                                },
                                                error: function () {
                                                    toastr.error('No se pudo guardar la modificación');
                                                },
                                                complete: function () {
                                                    $btn.prop('disabled', false).html('Guardar Cambios');
                                                }
                                            });
                                        });

                                    });
                                </script>

                            </div>
                        </div>
                    </div>
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
<!-- Page specific script -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Page specific script -->

</body>

</html>

