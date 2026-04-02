<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';
include_once '../vendor/quickchart/QuickChart.php';

if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('America/Santiago');

// Validar sesión
if (!isset($_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'])) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = mb_strtolower(trim($_SESSION['rolUsuario']), 'UTF-8');

// Validar idCarpeta
$idCarpeta = $_POST['idCarpeta'] ?? $_SESSION['idCarpeta'] ?? null;
if (!is_numeric($idCarpeta)) {
    header('Location: equipamiento_hbqp.php?noIdCarpeta=true');
    exit;
}
$idCarpeta = (int)$idCarpeta;
$_SESSION['idCarpeta'] = $idCarpeta;

//echo "<script>console.log('ID Carpeta: " . $idCarpeta . "');</script>";

// === Obtener datos generales de la categoría ===
$stmt = $conexion->prepare("SELECT * FROM categorias_equipos WHERE idCategoria = ?");
$stmt->bind_param("i", $idCarpeta);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();

//echo "<script>console.log('Carpeta: " . $categoria . "');</script>";

if (!$categoria) {
    header('Location: equipamiento_hbqp.php?categoriaNoExiste=true');
    exit;
}

$nomCategoria = $categoria['nomCategoria'];
$imagenCategoria = $categoria['imagenCategoria'];
$idDepartamento = $categoria['idDepartamento'];

// === Consulta única para estadística de equipos ===
$queryEquipos = "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN estadoEquipo = 'Operativo' THEN 1 ELSE 0 END) AS operativos,
        SUM(CASE WHEN estadoEquipo = 'OperativoObs' THEN 1 ELSE 0 END) AS conObservaciones,
        SUM(CASE WHEN estadoEquipo = 'En Mantenimiento' THEN 1 ELSE 0 END) AS mantenimiento,
        SUM(CASE WHEN estadoEquipo = 'Fuera de Servicio' THEN 1 ELSE 0 END) AS fueraServicio
    FROM equipamiento_hbqp
    WHERE idCategoria = ?
";
$stmt = $conexion->prepare($queryEquipos);
$stmt->bind_param("i", $idCarpeta);
$stmt->execute();
$equipos = $stmt->get_result()->fetch_assoc();

// === Consultas adicionales ===
function contarRegistros($conexion, $tabla, $campo, $idCarpeta)
{
    $stmt = $conexion->prepare("SELECT COUNT(*) AS cantidad FROM $tabla WHERE $campo = ?");
    $stmt->bind_param("i", $idCarpeta);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['cantidad'] ?? 0;
}

function sumarGastos($conexion, $idCarpeta)
{
    $stmt = $conexion->prepare("SELECT SUM(costoTotal) AS total FROM gasto_equipo_carpeta WHERE idCategoria = ?");
    $stmt->bind_param("i", $idCarpeta);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

$cantidadHojasTecnicas = contarRegistros($conexion, 'ordenes_trabajos_general', 'idCategoria', $idCarpeta);
$cuentaSoliCompra = contarRegistros($conexion, 'solicitudes_compra_sdo', 'idCategoria', $idCarpeta);
$cuentaArchivos = contarRegistros($conexion, 'archivos_equipamiento_hbqp', 'idCategoria', $idCarpeta);
$gastoTotal = sumarGastos($conexion, $idCarpeta);

// === Roles y rutas ===
$roles = [
    'personal' => 'sdo-personal',
    'administrativo' => 'sdo-admtec',
    'bodega pañol' => 'sdo-bodega-panol',
    'jefe tecnico' => 'sdo-jefestecnicos',
    'administrador' => 'sdo-administrador',
    'directiva' => 'sdo-directiva',
    'oficina tecnica' => 'sdo-oficinaTecnica',
];

$ruta = $roles[$rolUsuario] ?? null;
if (!$ruta) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipamiento HBQP</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

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
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Equipamiento HBQP</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="equipamiento_hbqp.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row" id="interfazPrincipal">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <?php
                                    $src = !empty($imagenCategoria) ? $imagenCategoria : 'img/controlCent-HBQP.png';
                                    $alt = !empty($imagenCategoria) ? $nomCategoria : 'Control Centralizado';

                                    $nomCategoria2 = mb_convert_case($nomCategoria, MB_CASE_UPPER, "UTF-8");
                                    ?>
                                    <img class="img-fluid" style="border-radius: 8px; width: 150px; height: 150px;" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>">

                                </div>

                                <br>
                                <h6 class=" text-center text-bold"><?php echo $nomCategoria2 ?> </h6>

                                <hr>

                                <?php
                                $registrados = contarequiposcategoria($idCarpeta);
                                $operativos = contareuiposoperativos($idCarpeta);
                                $observaciones = contarequiposobs($idCarpeta);
                                $mantenimiento = contarequiposmanteni($idCarpeta);
                                $fuera = contarequiposfallas($idCarpeta);
                                $valor = obtenerValorCompletoSistema($idCarpeta);
                                $extraviados = contarequiposextraviados($idCarpeta);

                                $estadisticas = [
                                    [
                                        'titulo' => 'Valor Sistema',
                                        'valor' => '$ ' . number_format($valor, 0, ',', '.'),
                                        'color' => 'purple',
                                        'icono' => 'fa-money-bill-wave',
                                        'modal' => true //abre modal
                                    ],
                                    [
                                        'titulo' => 'Equipos Registrados',
                                        'valor' => number_format($registrados, 0, ',', '.'),
                                        'color' => 'blue',
                                        'icono' => 'fa-desktop',
                                        'modal' => false // no abre modal
                                    ],
                                    [
                                        'titulo' => 'Equipos Operativos',
                                        'valor' => number_format($operativos, 0, ',', '.'),
                                        'color' => 'green',
                                        'icono' => 'fa-check',
                                        'modal' => false // no abre modal
                                    ],
                                    [
                                        'titulo' => 'Equipos Con Observaciones',
                                        'valor' => number_format($observaciones, 0, ',', '.'),
                                        'color' => 'gray',
                                        'icono' => 'fa-cog',
                                        'modal' => true //abre modal
                                    ],
                                    [
                                        'titulo' => 'Equipos en Mantenimiento',
                                        'valor' => number_format($mantenimiento, 0, ',', '.'),
                                        'color' => 'yellow',
                                        'icono' => 'fa-wrench',
                                        'modal' => true //abre modal
                                    ],
                                    [
                                        'titulo' => 'Equipos Fuera de Servicio',
                                        'valor' => number_format($fuera, 0, ',', '.'),
                                        'color' => 'red',
                                        'icono' => 'fa-skull-crossbones',
                                        'modal' => true //abre modal
                                    ],
                                    [
                                        'titulo' => 'Equipos Extraviados',
                                        'valor' => number_format($extraviados, 0, ',', '.'),
                                        'color' => 'info',
                                        'icono' => 'fa-question',
                                        'modal' => true //abre modal
                                    ]
                                ];

                                foreach ($estadisticas as $index => $item) {
                                    $tipo = $item['titulo'];
                                    $dataAttrs = $item['modal']
                                        ? "data-toggle='modal' data-target='#modalEstadistica' data-tipo='{$tipo}' data-color='{$item['color']}'"
                                        : "";
                                    ?>
                                    <div class="mb-1 px-2 py-1 text-white text-center bg-<?= $item['color'] ?>"
                                            style="
                                                    border-radius: 6px;
                                                    font-size: 9.5pt;
                                                    line-height: 1.2;
                                                    cursor: <?= $item['modal'] ? 'pointer' : 'default' ?>;
                                                    min-height: 34px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    gap: 6px;
                                                    "
                                        <?= $dataAttrs ?>>
                                        <i class="fas <?= $item['icono'] ?>" style="font-size: 11pt;"></i>
                                        <span><?= $item['titulo'] ?>:</span>
                                        <strong><?= $item['valor'] ?></strong>
                                    </div>
                                <?php }
                                ?>

                                <!-- Modal AJAX genérico -->
                                <div class="modal fade" id="modalEstadistica" tabindex="-1" role="dialog" aria-labelledby="modalEstadisticaLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="modalEstadisticaLabel">Detalle Estadística</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="contenidoModalEstadistica">
                                                <div class="text-center">
                                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                                    <p>Cargando información...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div id="boxResumenMantenciones"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="col-12">
                        <div class="card card-success card-outline">
                            <div class="card-header d-flex p-0 bg-success text-white">

                                <?php
                                $nomUsuarioMPI = $_SESSION['nomUsuario'];

                                $tabs = [
                                    [
                                        'id' => 'tabEquiposReg',
                                        'titulo' => 'Equipos Registrados',
                                        'visible' => true,
                                        'url' => '/sdo-includes/equipamiento/equipamiento_listado.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabModeloEquipos',
                                        'titulo' => 'Modelos de Equipos',
                                        'visible' => true,
                                        'url' => '/sdo-includes/equipamiento/equipamiento_modelos.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabDocumentos',
                                        'titulo' => 'Documentos',
                                        'visible' => true,
                                        'url' => '/sdo-includes/equipamiento/equipamiento_documentos.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tablle',
                                        'titulo' => 'Ibernex',
                                        'visible' => ($idCarpeta == 1),
                                        'url' => '/sdo-includes/equipamiento/equipamiento_lle.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabumas',
                                        'titulo' => 'Unidades Manejadoras de Aire (UMAS)',
                                        'visible' => ($idCarpeta == 2),
                                        'url' => '/sdo-includes/equipamiento/equipamiento_umas.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabProgramacion',
                                        'titulo' => 'Programacion',
                                        'visible' => ($idCarpeta == 71),
                                        'url' => '/sdo-includes/equipamiento/equipamiento_programacion.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabFechas',
                                        'titulo' => 'Calendario Mantenimiento',
                                        'visible' => ($idCarpeta == 71),
                                        'url' => '/sdo-includes/equipamiento/equipamiento_mantenimiento.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabVAV',
                                        'titulo' => 'Programacion VAV',
                                        'visible' => ($idCarpeta == 2),
                                        'url' => '/sdo-includes/equipamiento/equipamiento_programacion_vav.php' // URL específica
                                    ],
                                ];
                                ?>

                                <ul class="nav nav-pills mr-auto p-2 small">
                                    <?php
                                    $primeraActiva = false;

                                    foreach ($tabs as $tab) {
                                        if ($tab['visible']) {

                                            $active = (!$primeraActiva) ? 'active' : '';
                                            $primeraActiva = true;
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?= $active ?> text-white"
                                                        href="#<?= $tab['id'] ?>"
                                                        data-toggle="tab"
                                                        data-url="<?= $tab['url'] ?>"> <?= $tab['titulo'] ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-content">
                                    <?php
                                    $primeraActiva = false;

                                    foreach ($tabs as $tab) {
                                        if ($tab['visible']) {
                                            // Determinamos cuál es la pestaña que se muestra al cargar la página
                                            $activeClass = (!$primeraActiva) ? 'show active' : '';
                                            $primeraActiva = true;
                                            ?>

                                            <div class="tab-pane fade <?= $activeClass ?>"
                                                    id="<?= $tab['id'] ?>"
                                                    role="tabpanel"
                                                    aria-labelledby="<?= $tab['id'] ?>-tab">

                                                <div class="tab-loader" data-url="<?= $tab['url'] ?>">
                                                    <div class="text-center p-5 text-muted">
                                                        <i class="fas fa-circle-notch fa-spin fa-2x"></i>
                                                        <p class="mt-2">Cargando sección...</p>
                                                    </div>
                                                </div>

                                            </div>

                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022. Derechos Reservados.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<script>
    $(document).ready(function () {
        $('#modalEstadistica').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que abre el modal
            var tipo = button.data('tipo');
            tipo = decodeURIComponent(tipo.replace(/\+/g, ' '));

            var color = button.data('color');     // Color del botón
            var modal = $(this);

            // Limpiar colores anteriores del header
            var header = modal.find('.modal-header');
            header.removeClass(function (index, className) {
                return (className.match(/(^|\s)bg-\S+/g) || []).join(' ');
            });

            // Aplicar color nuevo
            header.addClass('bg-' + color);

            modal.find('.modal-title').text(tipo);
            // Mostrar loader mientras carga
            $('#contenidoModalEstadistica').html(`
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Cargando información...</p>
            </div>
        `);

            let urlDestino = '';
            if(tipo === 'Valor Sistema'){
                urlDestino = '/sdo-includes/equipamiento_valores_sistemas.php';
            }else{
                urlDestino = '/sdo-includes/equipamiento_cargar_valores.php';
            }

            // Llamada AJAX
            $.ajax({
                url: urlDestino,
                method: 'POST',
                data: {
                    tipo: tipo,
                    idCategoria: <?= $idCarpeta ?>
                },
                success: function (response) {
                    $('#contenidoModalEstadistica').html(response);

                    // Inicializar DataTable en tabla cargada dinámicamente

                },
                error: function () {
                    $('#contenidoModalEstadistica').html('<p class="text-danger">Error al cargar los datos.</p>');
                }
            });
        });


        $(document).on('click', '.btn-infoEquipo', function () {
            var idEquipo = $(this).data('idequipoinfo');
            var idCategoria = $(this).data('idcategoriainfo');
            var tipomenu = $(this).data('tipomenu');
            console.log('idEquipo Capturado:', idEquipo); // Verifica si el ID está siendo capturado correctamente
            console.log('idCategoria Capturado:', idCategoria); // Verifica si el ID está siendo capturado correctamente
            console.log('tipoMenu Capturado:', tipomenu); // Verifica si el ID está siendo capturado correctamente

            if (tipomenu === 'nuevo') {

                var form = $('<form>', {
                    'action': 'equipo_menuNuevo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                    'method': 'POST',
                });
            } else {

                var form = $('<form>', {
                    'action': 'equipo_menuAntiguo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                    'method': 'POST',
                    'target': '_blank',
                });
            }


            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idEquipo',
                'value': idEquipo
            }));

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idCategoria',
                'value': idCategoria
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });
    });
</script>

<script>
    $(function () {
        const idCarpeta = <?= (int)$idCarpeta ?>;

        $.ajax({
            url: '/sdo-includes/equipamiento_mantenimiento_resumen.php',
            type: 'POST',
            data: { idCarpeta },
            success: function (html) {
                $('#boxResumenMantenciones').html(html);
            },
            error: function () {
                $('#boxResumenMantenciones').html('<div class="alert alert-danger">Error AJAX</div>');
            }
        });

    });
</script>

<script>
    $(document).ready(function() {
        // 1. Cargar la pestaña activa por defecto al entrar
        var activeTab = $('.nav-link.active');
        if (activeTab.length) {
            ejecutarCarga(activeTab);
        }

        // 2. Al hacer clic en cualquier pestaña
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            ejecutarCarga($(e.target));
        });
    });

    function ejecutarCarga(elemento) {
        var targetId = elemento.attr("href");
        var urlDestino = elemento.data("url");
        var contenedor = $(targetId).find('.tab-loader');

        // Obtenemos el idCarpeta desde PHP para pasarlo al AJAX
        var idCarpetaActual = <?= (int)$idCarpeta ?>;

        // Validamos que tenga URL y que NO tenga la clase 'ya-cargado'
        if (!contenedor.hasClass('ya-cargado') && urlDestino) {

            $.ajax({
                url: urlDestino,
                type: 'GET',
                data: { idCarpeta: idCarpetaActual }, // <-- ¡ESTA ES LA MAGIA!
                success: function(html) {
                    // Insertamos el HTML de respuesta
                    contenedor.html(html);
                    // Le agregamos la clase para que no vuelva a cargar la próxima vez que le des clic
                    contenedor.addClass('ya-cargado');
                },
                error: function(xhr, status, error) {
                    console.log("Error AJAX:", error);
                    contenedor.html('<div class="alert alert-danger m-3">Error al conectar con la ruta: ' + urlDestino + '</div>');
                }
            });
        }
    }
</script>

</body>

</html>


