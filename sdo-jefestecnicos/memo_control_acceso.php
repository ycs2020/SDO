<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$horaActual = date('H');
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$idUsuario = $_SESSION['idUsuario'];

if (isset($_POST['ruta'])) {
    $rutaArchivo = $_POST['ruta'];

    // Seguridad: evita rutas maliciosas
    $rutaArchivo = realpath($rutaArchivo);

    if ($rutaArchivo === false || !file_exists($rutaArchivo)) {
        echo "El archivo no existe o la ruta es inválida.";
        exit;
    }

    // Verifica permisos de lectura
    if (!is_readable($rutaArchivo)) {
        echo "El archivo no tiene permisos de lectura.";
        exit;
    }

    $mimeType = mime_content_type($rutaArchivo);
    if ($mimeType === false) {
        echo "No se pudo obtener el tipo MIME del archivo.";
        exit;
    }

    // Limpia cualquier salida previa
    if (ob_get_length()) ob_end_clean();

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: inline; filename="' . basename($rutaArchivo) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($rutaArchivo));

    flush(); // asegúrate de enviar encabezados
    readfile($rutaArchivo);
    exit;
}



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Memorandum de Control de Acceso</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">

        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Memorandums de Control de Acceso</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modalIngresarMemorandum">
                                            <i class="fas fa-plus"></i> Ingresar Memorándum
                                        </button>

                                        <div class="modal fade" id="modalIngresarMemorandum" tabindex="-1" role="dialog" aria-labelledby="modalIngresarMemorandumLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="modalIngresarMemorandumLabel"><i class="fas fa-file-alt mr-2"></i>Agregar Memorándum</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/guardar_memos_control_acceso.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body">

                                                            <div class="form-group">
                                                                <label for="numMemo"><i class="fas fa-hashtag mr-1"></i>Número del Memorándum:</label>
                                                                <input type="number" required class="form-control" id="numMemo" name="numMemo" placeholder="Ej: 1234">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="nomEmisor"><i class="fas fa-user-edit mr-1"></i>Nombre del Emisor:</label>
                                                                <input type="text" required class="form-control" id="nomEmisor" name="nomEmisor" placeholder="Ej: Juan Pérez">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="nomUnidad"><i class="fas fa-building mr-1"></i>Nombre de la Unidad:</label>
                                                                <input type="text" required class="form-control" id="nomUnidad" name="nomUnidad" placeholder="Ej: Unidad de Control">
                                                            </div>

                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="fechaRecepcion"><i class="fas fa-calendar-check mr-1"></i>Fecha de Recepción:</label>
                                                                    <input type="date" required class="form-control" id="fechaRecepcion" name="fechaRecepcion">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="fechaEmision"><i class="fas fa-calendar-alt mr-1"></i>Fecha de Emisión:</label>
                                                                    <input type="date" required class="form-control" id="fechaEmision" name="fechaEmision">
                                                                </div>
                                                            </div>

                                                            <hr>

                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <label><i class="fas fa-users mr-1"></i>Funcionario(s) Asociado(s):</label>
                                                                <button id="agregarFila" type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Agregar nuevo funcionario">
                                                                    <i class="fas fa-plus"></i> Funcionario
                                                                </button>
                                                            </div>

                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-sm" id="tablaFuncionarios">
                                                                    <thead class="thead-dark">
                                                                    <tr class="text-center text-uppercase">
                                                                        <th>Nombre del Funcionario</th>
                                                                        <th>RUT</th>
                                                                        <th>Acción</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td><input type="text" required class="form-control" id="nomFuncionario1" name="nomFuncionario1" placeholder="Ej: María Soto"></td>
                                                                        <td><input type="text" required class="form-control" id="rutFuncionario1" name="rutFuncionario1" placeholder="Ej: 12.345.678-9"></td>
                                                                        <td class="text-center text-muted">No se puede eliminar</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <hr>

                                                            <div class="form-group">
                                                                <label for="archivos[]"><i class="fas fa-paperclip mr-1"></i>Subir Archivo(s):</label>
                                                                <input type="file" class="form-control-file" name="archivos[]" id="archivos[]" multiple>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <input type="hidden" id="contarfuncionario" name="contarfuncionario" value="1">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalBuscarFuncionario">
                                            <i class="fas fa-eye"></i> Buscar Funcionario
                                        </button>

                                        <div class="modal fade" id="modalBuscarFuncionario" tabindex="-1" role="dialog" aria-labelledby="modalBuscarFuncionarioLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title" id="modalBuscarFuncionarioLabel">
                                                            <i class="fas fa-users mr-2"></i>Listado de Funcionarios General
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="table-responsive">
                                                            <table id="tablaBuscar" class="table table-bordered table-hover text-center w-100">
                                                                <thead class="thead-dark text-uppercase">
                                                                <tr>
                                                                    <th>Nombre del Funcionario</th>
                                                                    <th>RUT</th>
                                                                    <th>Ver Acta</th>
                                                                    <th>N° Memos Plataforma</th>
                                                                </tr>
                                                                </thead>
                                                                <tfoot class="thead-dark text-uppercase">
                                                                <tr>
                                                                    <th>Nombre del Funcionario</th>
                                                                    <th>RUT</th>
                                                                    <th>Ver Acta</th>
                                                                    <th>N° Memos Plataforma</th>
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
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <table id="tableListadoMemos" class="table table-bordered table-hover text-center w-100">
                                    <thead class="bg-secondary text-white">
                                    <tr>
                                        <th>N° Memo Plataforma</th>
                                        <th>Fecha Recepción</th>
                                        <th>Nombre Unidad</th>
                                        <th>Nombre Emisor</th>
                                        <th>Fecha Emisión</th>
                                        <th>N° Memo</th>
                                        <th>Detalles</th>
                                        <th>Funcionarios</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-secondary text-white">
                                    <tr>
                                        <th>N° Memo Plataforma</th>
                                        <th>Fecha Recepción</th>
                                        <th>Nombre Unidad</th>
                                        <th>Nombre Emisor</th>
                                        <th>Fecha Emisión</th>
                                        <th>N° Memo</th>
                                        <th>Detalles</th>
                                        <th>Funcionarios</th>
                                    </tr>
                                    </tfoot>
                                </table>

                                <!-- Aquí se agregarán los modales "Ver Funcionarios" -->
                                <div id="modalesFuncionarios"></div>


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



<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php
if (isset($_GET['registroExitoso'])) {
    echo "<script>toastr.success('Memorandum Ingresado con Exito.');</script>";
}
if (isset($_GET['error']) || isset($GET['error1'])) {
    echo "<script>toastr.error('Error al ingresar Memorandum.');</script>";
}
if (isset($_GET['errorArchivoSubido'])) {
    echo "<script>toastr.error('Memorandum ya se encuentra ingresado.');</script>";
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnAgregarFila = document.getElementById('agregarFila');
        const tabla = document.getElementById('tablaFuncionarios').querySelector('tbody');
        const inputContador = document.getElementById('contarfuncionario');
        let correlativo = 2;

        btnAgregarFila.addEventListener('click', () => {
            const nuevaFila = document.createElement('tr');

            // Nombre
            const tdNombre = document.createElement('td');
            const inputNombre = document.createElement('input');
            inputNombre.type = 'text';
            inputNombre.className = 'form-control';
            inputNombre.required = true;
            tdNombre.appendChild(inputNombre);

            // RUT
            const tdRut = document.createElement('td');
            const inputRut = document.createElement('input');
            inputRut.type = 'text';
            inputRut.className = 'form-control';
            inputRut.required = true;
            tdRut.appendChild(inputRut);

            // Botón Eliminar
            const tdEliminar = document.createElement('td');
            tdEliminar.className = 'text-center';
            const btnEliminar = document.createElement('button');
            btnEliminar.className = 'btn btn-danger btn-sm';
            btnEliminar.innerHTML = '<i class="fas fa-trash"></i> Eliminar';
            btnEliminar.type = 'button';
            btnEliminar.onclick = () => {
                tabla.removeChild(nuevaFila);
                actualizarIDs();
            };
            tdEliminar.appendChild(btnEliminar);

            // Armar la fila
            nuevaFila.appendChild(tdNombre);
            nuevaFila.appendChild(tdRut);
            nuevaFila.appendChild(tdEliminar);

            tabla.appendChild(nuevaFila);
            actualizarIDs();
        });

        function actualizarIDs() {
            let index = 1;
            const filas = tabla.querySelectorAll('tr');
            filas.forEach((fila) => {
                const inputs = fila.querySelectorAll('input');
                if (inputs.length === 2) {
                    inputs[0].name = 'nomFuncionario' + index;
                    inputs[0].id = 'nomFuncionario' + index;
                    inputs[1].name = 'rutFuncionario' + index;
                    inputs[1].id = 'rutFuncionario' + index;
                    index++;
                }
            });
            inputContador.value = filas.length;
        }

        $('[data-toggle="tooltip"]').tooltip(); // Activa tooltips
    });
</script>


<script>
    $(document).ready(function () {
        $('#tablaBuscar').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ordering: false,
            lengthChange: false,
            searching: true,
            ajax: {
                url: '/sdo-includes/memos_ccaa_buscar_funcionario.php',
                type: 'POST'
            },
            columns: [
                { data: 'nombre' },
                { data: 'rut' },
                { data: 'acta' },
                { data: 'memos' }
            ],
            createdRow: function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
            },
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
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        const tablaMemos = $('#tableListadoMemos').DataTable({
            ajax: {
                url: '/sdo-includes/memos_ccaa_listar_funcionario.php',
                type: 'POST',
                dataSrc: function(json) {
                    // Agregar modales dinámicos a contenedor
                    $('#modalesFuncionarios').html(json.modales);
                    return json.data;
                }
            },
            columns: [
                { data: 'id' },
                { data: 'fechaRecepcion' },
                { data: 'unidad' },
                { data: 'emisor' },
                { data: 'fechaEmision' },
                { data: 'numeroMemo' },
                { data: 'archivo' },
                { data: 'funcionarios' }
            ],
            "lengthChange": false,
            responsive: true,
            ordering: false,
            autoWidth: false,
            language: {
                zeroRecords: "No se han encontrado Registros.",
                lengthMenu: "Visualizando _MENU_ Registros por Página",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No existen Registros.",
                infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                search: "Buscar:",
                processing: "Procesando...",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente"
                }
            }
        });

        // Inicializar DataTable en cada tabla modal al abrirla
        $(document).on('shown.bs.modal', '.modal', function () {
            const table = $(this).find('.table').first();
            if (!$.fn.DataTable.isDataTable(table)) {
                table.DataTable({
                    ordering: false,
                    paging: false,
                    searching: true,
                    info: false,
                    responsive: true,
                    language: {
                        zeroRecords: "No se han encontrado Funcionarios.",
                        lengthMenu: "Visualizando _MENU_ Funcionarios por Página",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No existen Registros.",
                        infoFiltered: "(Filtrado desde _MAX_ Funcionarios Totales)",
                        search: "Buscar:",
                        processing: "Procesando...",
                        paginate: {
                            previous: "Anterior",
                            next: "Siguiente"
                        }
                    }
                });
            }
        });
    });
</script>




</body>
</html>

