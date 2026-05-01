<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = trim($_SESSION['nomUsuario'] ?? '');
$idDepartamento = $_SESSION['idDepartamento'] ?? 0;
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'] ?? 0;

date_default_timezone_set('America/Santiago');


// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema BMS - Control Centralizado</title>
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
    <!-- summernote -->
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>
        .zoom-card {
            transition: transform 0.3s;
        }

        .zoom-card:hover {
            transform: scale(1.1);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .contador {
            display: inline-block;
            background-color: rgb(40, 82, 167);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        }

    </style>
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
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-body d-flex align-items-center">
                                <h5 class="mb-0">Equipamiento</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-body d-flex align-items-center">
                                <a href="index.php" class="btn btn-danger btn-block m-0">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card">

                            <?php
                            $departamentosVisibles = [
                                "Electricidad" => "Electricidad",
                                "Equipos de Apoyo Clínico" => "Equipos de Apoyo Clínico",
                                "Equipos Industriales" => "Equipos Industriales",
                                "Equipos Médicos" => "Equipos Médicos",
                                "Incendio y Seguridad" => "Incendio y Seguridad",
                                "Infraestructura" => "Infraestructura",
                                "Inst. Térmicas, Climatización y Refrigeración" => "Climatización",
                                "Instrumentación" => "Control Centralizado",
                                "Movilización" => "Movilización"
                            ];
                            ?>
                            <div class="card-header d-flex p-0 text-center bg-success text-white">
                                <ul style="font-size: 10pt;" class="nav nav-pills mr-auto ml-auto p-2 text-center text-white">
                                    <?php
                                    // Consultar todos los departamentos
                                    $sqlSelect = "SELECT * FROM departamentos";
                                    $resSelect = mysqli_query($conexion, $sqlSelect);

                                    $deptosParaMostrar = [];

                                    if ($resSelect && mysqli_num_rows($resSelect) > 0) {
                                        while ($row = mysqli_fetch_assoc($resSelect)) {
                                            $nombreOriginal = $row['nomDepartamento'];

                                            // Solo incluir si está en el arreglo de visibles
                                            if (isset($departamentosVisibles[$nombreOriginal])) {
                                                $deptosParaMostrar[] = [
                                                    'id' => $row['idDepartamento'],
                                                    'nombreVisible' => $departamentosVisibles[$nombreOriginal],
                                                    'nombreOriginal' => $nombreOriginal
                                                ];
                                            }
                                        }
                                    }

                                    // Ordenar por el nombre visible
                                    usort($deptosParaMostrar, function ($a, $b) {
                                        return strcmp($a['nombreVisible'], $b['nombreVisible']);
                                    });

                                    // Mostrar los tabs
                                    foreach ($deptosParaMostrar as $depto) {
                                        $isActive = ($depto['nombreOriginal'] === 'Instrumentación') ? 'active' : '';
                                        ?>
                                        <li class="nav-item text-white">
                                            <a class="nav-link <?php echo $isActive; ?> text-white" href="#vistaDepto<?php echo $depto['id']; ?>" data-toggle="tab">
                                                <?php echo $depto['nombreVisible']; ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-3">
                                    <?php
                                    foreach ($deptosParaMostrar as $depto) {
                                        $isActive = ($depto['nombreOriginal'] === 'Instrumentación') ? 'show active' : '';
                                        ?>
                                        <div class="tab-pane fade <?php echo $isActive; ?>" id="vistaDepto<?php echo $depto['id']; ?>">
                                            <div class="cargando">Cargando contenido...</div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <script>
                                    $(document).ready(function () {
                                        $('.nav-link').on('shown.bs.tab', function (e) {
                                            var targetId = $(e.target).attr('href'); // ej: #vistaDepto5
                                            var idDepto = targetId.replace('#vistaDepto', '');

                                            // Solo cargar si no se ha cargado antes
                                            var container = $(targetId);
                                            if (!container.data('loaded')) {
                                                $.ajax({
                                                    url: '/sdo-includes/cargar_contenido_departamento_equipamiento.php',
                                                    method: 'POST',
                                                    data: {idDepartamento: idDepto},
                                                    beforeSend: function () {
                                                        container.html('<div class="text-center text-muted">Cargando...</div>');
                                                    },
                                                    success: function (response) {
                                                        container.html(response);
                                                        container.data('loaded', true); // Marcar como ya cargado
                                                    },
                                                    error: function () {
                                                        container.html('<div class="text-danger">Error al cargar los datos.</div>');
                                                    }
                                                });
                                            }
                                        });

                                        // Opcional: cargar el primero al iniciar
                                        $('.nav-link.active').trigger('shown.bs.tab');
                                    });


                                </script>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
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
// Aquí tu PHP con los toastr según corresponda

if (isset($_GET['exitoCrearCategoria'])) {
    echo "<script>toastr.success('Categoria Creada con Exito.');</script>";
}
if (isset($_GET['errorCrearCategoria'])) {
    echo "<script>toastr.error('Error al Crear la Categoria.');</script>";
}

if (isset($_GET['exitoModificarInventario'])) {
    echo "<script>toastr.success('Cantidad de Equipo Modificada con Exito.');</script>";
}
if (isset($_GET['errorModificarInventario'])) {
    echo "<script>toastr.error('Error al Modificar Cantidad de Equipo.');</script>";
}
?>
<!-- Page specific script -->

<script>
    // Obtener referencia al campo de entrada de archivo
    const inputImagen = document.getElementById('imagen');
    // Obtener referencia al div de vista previa
    const vistaPreviaDiv = document.getElementById('vista-previa');

    // Escuchar el evento change del campo de entrada de archivo
    inputImagen.addEventListener('change', function (e) {
        // Obtener la imagen seleccionada
        const imagenSeleccionada = e.target.files[0];

        // Crear un objeto URL para la imagen seleccionada
        const imagenURL = URL.createObjectURL(imagenSeleccionada);

        // Crear un elemento de imagen para mostrar la vista previa
        const imagenPrevia = document.createElement('img');
        imagenPrevia.src = imagenURL;
        imagenPrevia.classList.add('img-fluid');

        // Limpiar el div de vista previa antes de añadir la nueva imagen
        vistaPreviaDiv.innerHTML = '';

        // Agregar la imagen de vista previa al div
        vistaPreviaDiv.appendChild(imagenPrevia);
    });
</script>
<script>
    $('[data-id]').click(function () {
        var dataId = $(this).attr('data-id');
        $('#modalEditarCategoria').data('id', dataId).modal('show');
        var elementos = {};
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtiene_datos_categoria.php',
            data: {
                'id': dataId
            },
            success: function (response) {
                // console.log(response);
                var datos = JSON.parse(response);

                elementos.nomCategoria = datos.nomCategoria;
                elementos.idDepartamento = datos.idDepartamento;
                elementos.imagenCategoria = datos.imagenCategoria;

                $('#idCategoria').val(dataId);
                $('#nombreCategoriaEdita').val(elementos.nomCategoria);
                $('#departamentoCategoriaEdita').val(elementos.idDepartamento);
                $('#img').attr('src', elementos.imagenCategoria);

            }
        })
    })
</script>

<script>
    $(document).ready(function () {
        $('#tabla-equipo-observacion').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    function obtenerid(idCategoria) {
        console.log(idCategoria);

</script>


<script src="js/fullScreen.js"></script>


<!-- MODAL VALOR SISTEMA -->
<!-- Modal para mostrar detalle del valor del sistema -->
<div class="modal fade" id="modalValorSistema" tabindex="-1" role="dialog" aria-labelledby="modalValorSistemaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title" id="modalValorSistemaLabel">Detalle del Valor del Sistema</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoModalValorSistema">
                Cargando datos...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.btn-valor-sistema', function () {
        var idCategoria = $(this).data('id');
        $('#contenidoModalValorSistema').html('Cargando datos del sistema...');

        $.ajax({
            url: '/sdo-includes/ajax_cargar_detalle_sistema.php',
            method: 'POST',
            data: {idCategoria: idCategoria},
            success: function (response) {
                // console.log('Respuesta AJAX:', response); // VER en consola
                $('#contenidoModalValorSistema').html(response);

                $('#tablaValorEquipos').DataTable({
                    "paging": true,
                    "iDisplayLength": 10,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                        "zeroRecords": "No se han encontrado Registros.",
                        "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                        "infoEmpty": "No existen Registros.",
                        "search": "Buscar:",
                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                        "paginate": {
                            "previous": "Anterior",
                            "next": "Siguiente"
                        }
                    },
                });

            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText, status, error);
                $('#contenidoModalValorSistema').html('<div class="text-danger">Error al cargar los datos del sistema.</div>');
            }
        });

        console.log('Pulsaste');
    });
</script>

<!-- FIN MODAL VALOR SISTEMA -->



<!-- MODAL FUERA DE SERVICIO -->
<div class="modal fade" id="modalFueraServicio" tabindex="-1" role="dialog" aria-labelledby="modalFueraServicioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalFueraServicioLabel">Equipos Fuera de Servicio</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoModalFueraServicio">
                Cargando datos...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.btn-fuera-servicio', function () {
        var idCategoria = $(this).data('id');
        $('#contenidoModalFueraServicio').html('Cargando datos del sistema...');

        $.ajax({
            url: '/sdo-includes/ajax_cargar_equipos_fuera_servicio.php',
            method: 'POST',
            data: {idCategoria: idCategoria},
            success: function (response) {
                // console.log('Respuesta AJAX:', response); // VER en consola
                $('#contenidoModalFueraServicio').html(response);

                $('#tablaEquiposFueraServicio').DataTable({
                    "paging": true,
                    "iDisplayLength": 10,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                        "zeroRecords": "No se han encontrado Registros.",
                        "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                        "infoEmpty": "No existen Registros.",
                        "search": "Buscar:",
                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                        "paginate": {
                            "previous": "Anterior",
                            "next": "Siguiente"
                        }
                    },
                });

            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText, status, error);
                $('#contenidoModalFueraServicio').html('<div class="text-danger">Error al cargar los datos del sistema.</div>');
            }
        });

        // console.log('Pulsaste');
    });
</script>

<!-- FIN MODAL FUERA DE SERVICIO -->

</body>

</html>

