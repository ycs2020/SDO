<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Centralización de Variables de Sesión
$userUsuario = $_SESSION['userUsuario'] ?? null;
$idUsuario = $_SESSION['idUsuario'] ?? null;
$nomUsuario = $_SESSION['nomUsuario'] ?? null;
$rolUsuario = strtolower($_SESSION['rolUsuario'] ?? '');

date_default_timezone_set('America/Santiago');
$fecha = date('d-m-Y H:i');

function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// 2. Mapeo de Rutas
$rutasRoles = [
    "personal" => "sdo-personal",
    "administrativo" => "sdo-admtec",
    "bodega pañol" => "sdo-bodega-panol",
    "jefe tecnico" => "sdo-jefestecnicos",
    "administrador" => "sdo-administrador",
    "directiva" => "sdo-directiva",
    "oficina tecnica" => "sdo-oficinaTecnica"
];

if (isset($rutasRoles[$rolUsuario])) {
    $tipoUsuario = $rolUsuario;
    $rutaDireccion = $rutasRoles[$rolUsuario];
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

// 3. Lógica de Obtención de ID de Equipo
$idEquipo = $_GET['idEquipo'] ?? $_POST['idEquipo'] ?? $_SESSION['idEquipo'] ?? null;

if (!$idEquipo || !is_numeric($idEquipo)) {
    header("Location: ../$rutaDireccion/carpeta-equipo.php?errorCargarEquipo=true");
    exit;
}

$_SESSION['idEquipo'] = $idEquipo;

// 4. Consulta Principal
$stmt = $conexion->prepare("SELECT e.*, c.nomCategoria 
                             FROM equipamiento_hbqp e
                             LEFT JOIN categorias_equipos c ON e.idCategoria = c.idCategoria
                             WHERE e.id = ?");
$stmt->bind_param("i", $idEquipo);
$stmt->execute();
$res = $stmt->get_result();
$equipo = $res->fetch_assoc();

if (!$equipo) {
    header("Location: carpeta-equipo.php?errorEquipoNoEncontrado=true");
    exit;
}

extract($equipo);

// 5. Consultas de Conteo
function obtenerConteo($conexion, $sql, $id)
{
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();
    return $data['total'] ?? 0;
}

$cantidadHojasTec = obtenerConteo($conexion, "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE idEquipo = ?", $idEquipo);
$totalSolicitudes = obtenerConteo($conexion, "SELECT COUNT(*) as total FROM solicitudes_compra_sdo WHERE idEquipo = ?", $idEquipo);
$cantidadSubEquipos = obtenerConteo($conexion, "SELECT COUNT(*) as total FROM equipamiento_hbqp WHERE idEquipoPadre = ?", $idEquipo);
$gastoTotal = obtenerConteo($conexion, "SELECT SUM(costoTotal) as total FROM gasto_equipo_carpeta WHERE idEquipo = ?", $idEquipo);
$cantidadArchivos = obtenerConteo($conexion, "SELECT COUNT(*) as total FROM archivos_equipamiento_hbqp WHERE idEquipo = ?", $idEquipo);

// 6. Generación de ID Próximo
$resMax = mysqli_query($conexion, "SELECT MAX(id) AS ultimo_id FROM equipamiento_hbqp");
$rowMax = $resMax->fetch_assoc();
$idGenerado = 'EQUIP-HBQP-' . ($rowMax['ultimo_id'] + 1);

// 7. Lógica de Texto y Contadores Personalizados
$variableParaTablaFinalAntes = "";
if (!empty($nombreEquipo)) {
    $porciones = explode("ECB", $nombreEquipo);
    if (isset($porciones[1])) {
        $variableParaTablaAntes = 'ECB ' . $porciones[1];
        $porciones2 = explode("_", $variableParaTablaAntes);
        $variableParaTablaFinalAntes = $porciones2[0] . '-' . ($porciones2[1] ?? '');
    }
}

$contardigitalinput = contardigitalinput($variableParaTablaFinalAntes);
$contaranaloginput = contaranaloginput($variableParaTablaFinalAntes);
$contardigitaloutput = contardigitaloutput($variableParaTablaFinalAntes);
$contaranalogoutput = contaranalogoutput($variableParaTablaFinalAntes);

$contarambosinput = $contardigitalinput + $contaranaloginput;
$contarambosoutput = $contardigitaloutput + $contaranalogoutput;
$buscarequipoPadre = buscarequipoPadre($idEquipoPadre);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($nombreEquipo) ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <!-- Plugins Originales -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
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
    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center"> <!-- Alineación vertical al centro -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card shadow-sm mb-0"> <!-- mb-0 para evitar márgenes extra -->
                            <div class="card-body p-0"> <!-- Usamos card-body sin padding -->
                                <button style="pointer-events: none; border-radius: 3px;" class="btn btn-block btn-primary text-left">
                                    Equipamiento: <b><?= h($nombreEquipo); ?></b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12 mt-2 mt-md-0">
                        <div class="card shadow-sm mb-0">
                            <div class="card-body p-0">
                                <?php
                                if($idEquipoPadre > 0){
                                    $urlVolver = "equipamiento_detalle.php";
                                }else{
                                    $urlVolver = "carpeta-equipo.php";
                                }
                                ?>
                                <a href="<?= $urlVolver; ?>" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row d-flex align-items-stretch" id="interfazPrincipal">

                    <!-- BLOQUE 1: Perfil Visual -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 d-flex">
                        <div class="card card-primary card-outline w-100 shadow">
                            <div class="card-body box-profile text-center">
                                <?php
                                $obtenerRutaImagenSegunModelo = obtenerRutaImagenSegunModelo($modelo);
                                $imgFinal = !empty($rutaImagen) ? $rutaImagen : (!empty($obtenerRutaImagenSegunModelo) ? $obtenerRutaImagenSegunModelo : "../sdo-administrador/img/controlCent-HBQP.png");
                                ?>
                                <img class="img-fluid mb-3" style="border-radius: 8px; width: 150px; height: 125px;" src="<?= $imgFinal; ?>" alt="Imagen Equipo">
                                <hr>
                                <ul class="list-group list-group-unbordered mb-3 text-left">
                                    <li class="list-group-item small"><b>Equipo</b> <a class="float-right text-primary"><b><?= h($nombreEquipo) ?></a></b></li>
                                    <li class="list-group-item small"><b>Categoría</b> <a class="float-right text-primary"><b><?= h($nomCategoria) ?></b></a></li>
                                    <li class="list-group-item small"><b>ID Sistema</b> <a class="float-right text-primary"><b><?= h($idGenerado) ?></b></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE 2: Operación (Modales AJAX) -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 d-flex">
                        <div class="card card-primary card-outline w-100 shadow">
                            <div class="card-header border-0"><h3 class="card-title text-bold"><i class="fas fa-microchip mr-2"></i> Operación</h3></div>
                            <div class="card-body d-flex flex-column justify-content-center">
                                <button type="button" class="btn btn-success btn-block text-left p-2 mb-1 btn-sdo-modal"
                                        data-accion="estado" data-id="<?= $idEquipo ?>" data-titulo="Modificar Estado del Equipo">
                                    <i class="fas fa-signal mr-2"></i> Estado: <b><?= $estadoEquipo ?></b>
                                </button>
                                <button type="button" class="btn btn-primary btn-block text-left p-2 mb-1 btn-sdo-modal"
                                        data-accion="observaciones" data-id="<?= $idEquipo ?>" data-titulo="Observaciones Técnicas">
                                    <i class="fas fa-comment-dots mr-2"></i> Observaciones
                                </button>
                                <button type="button" class="btn bg-purple btn-block text-left p-2 btn-sdo-modal"
                                        data-accion="tiempo" data-id="<?= $idEquipo ?>" data-titulo="Horas de Vuelo / Funcionamiento">
                                    <i class="fas fa-hourglass-half mr-2"></i> Tiempo Funcionamiento
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE 3: Administración y Costos (Redirect POST) -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 d-flex">
                        <div class="card card-primary card-outline w-100 shadow">
                            <div class="card-header border-0">
                                <h3 class="card-title text-bold"><i class="fas fa-folder-open mr-2"></i> Gestión Administrativa</h3>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center">

                                <!-- Botones de Acción con Alineación Flexbox -->
                                <button type="button" class="btn btn-primary btn-block text-left p-2 mb-1 btn-sdo-accion d-flex align-items-center justify-content-between"
                                        data-url="equipo-hojas-tecnicas.php" data-id="<?= $idEquipo ?>">
                                    <span><i class="fas fa-file-medical mr-2"></i> Hojas Técnicas</span>
                                    <span class="badge badge-light d-flex align-items-center justify-content-center" style="min-width: 30px; height: 20px;"><?= $cantidadHojasTec ?></span>
                                </button>

                                <button type="button" class="btn btn-secondary btn-block text-left p-2 mb-1 btn-sdo-accion d-flex align-items-center justify-content-between"
                                        data-url="equipo-gastos.php" data-id="<?= $idEquipo ?>">
                                    <span><i class="fas fa-hand-holding-usd mr-2"></i> Gastos Asociados</span>
                                    <span class="badge badge-light d-flex align-items-center justify-content-center" style="min-width: 50px; height: 20px;">$<?= number_format($gastoTotal, 0, ',', '.') ?></span>
                                </button>

                                <button type="button" class="btn btn-danger btn-block text-left p-2 mb-1 btn-sdo-accion d-flex align-items-center justify-content-between"
                                        data-url="equipo-solicitudes-compra.php" data-id="<?= $idEquipo ?>">
                                    <span><i class="fas fa-shopping-cart mr-2"></i> Solicitudes Compra</span>
                                    <span class="badge badge-light d-flex align-items-center justify-content-center" style="min-width: 30px; height: 20px;"><?= $totalSolicitudes ?></span>
                                </button>

                                <!-- mb-3 agregado para igualar el gap con el Resumen BOS -->
                                <button type="button" class="btn bg-pink btn-block text-left p-2 mb-1 btn-sdo-accion d-flex align-items-center justify-content-between"
                                        data-url="equipo-archivos.php" data-id="<?= $idEquipo ?>">
                                    <span><i class="fas fa-file-archive mr-2"></i> Archivos Adjuntos</span>
                                    <span class="badge badge-light d-flex align-items-center justify-content-center" style="min-width: 30px; height: 20px;"><?= $cantidadArchivos ?></span>
                                </button>

                                <!-- Reporte Resumen BOS alineado al tamaño de los superiores -->
                                <?php if (mb_strtoupper($nombreEquipo) === 'BACNET MSTP'): ?>
                                    <form action="../sdo-pdf/SDO-BOS-Detalle.php" method="post" target="_blank" class="m-0">
                                        <input type="hidden" name="idEquipo" value="<?= $idEquipo ?>">
                                        <button type="submit" class="btn btn-warning btn-block text-left p-2 mt-1 d-flex align-items-center" style="min-height: 42px;">
                                            <i class="fas fa-file-invoice mr-2"></i> Resumen BOS (PDF)
                                        </button>
                                    </form>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="card card-success card-outline">
                            <div class="card-header d-flex p-0 bg-success text-white">

                                <?php
                                $nomUsuarioMPI = $_SESSION['nomUsuario'];

                                $tabs = [
                                    [
                                        'id' => 'tabSubEquipos',
                                        'titulo' => 'SubEquipos',
                                        'visible' => true,
                                        'url' => '/sdo-includes/equipamiento_detalle/equipamiento_listado.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabDocumentos',
                                        'titulo' => 'Documentos',
                                        'visible' => true,
                                        'url' => '/sdo-includes/equipamiento_detalle/equipamiento_documentos.php' // URL específica
                                    ],
                                    [
                                        'id' => 'tabPlanificacion',
                                        'titulo' => 'Planificacion',
                                        'visible' => true,
                                        'url' => '/sdo-includes/equipamiento_detalle/equipamiento_planificacion.php' // URL específica
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
            </div>
        </section>
    </div>
    <footer class="main-footer"><strong>Copyright &copy; 2022. HBQP</strong></footer>
</div>

<!-- MODAL MAESTRO (AQUÍ REGRESA EL ESQUELETO) -->
<div class="modal fade" id="modalSdo" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- ⚠️ ELIMINADA LA CLASE shadow-lg DE AQUÍ -->
    <div class="modal-dialog modal-xl modal-dialog-centered" id="modalSdoContenedorDinamico">
        <!-- El AJAX inyectará el contenido aquí -->
    </div>
</div>

<!-- SCRIPTS PRESERVADOS -->
<script src="js/dark-mode.js"></script>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script>
    $(document).ready(function () {
        // Lógica de Modales (Abre el Modal AJAX)
        const moduloRutas = {
            'estado': '../sdo-includes/equipamiento/equipamiento_estado.php',
            'observaciones': '../sdo-includes/equipamiento/equipamiento_observaciones.php',
            'tiempo': '../sdo-includes/equipamiento/equipamiento_tiempo.php'
        };

        $('.btn-sdo-modal').on('click', function () {
            const accion = $(this).data('accion');
            const id = $(this).data('id');
            const url = moduloRutas[accion];

            // Limpiamos el contenedor
            $('#modalSdoContenedorDinamico').empty();

            $.ajax({
                url: url,
                type: 'POST',
                data: { idEquipo: id },
                success: function (data) {
                    // 1. Inyectas los datos en el contenedor interno
                    $('#modalSdoContenedorDinamico').html(data);

                    // 2. Abres el modal apuntando al ID PRINCIPAL (#modalSdo)
                    $('#modalSdo').modal('show');
                    $('#modalSdo').modal('handleUpdate');
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                }
            });
        });

        // Lógica de Redirección (POST Form dinámico de la columna derecha)
        $('.btn-sdo-accion').on('click', function() {
            const urlDestino = $(this).data('url');
            const equipoId = $(this).data('id');
            const form = $('<form>', { action: urlDestino, method: 'POST' })
                .append($('<input>', { type: 'hidden', name: 'idEquipo', value: equipoId }));
            $('body').append(form);
            form.submit();
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
        var idCarpetaActual = <?= (int)$idCategoria ?>;
        var idEquipoActual = <?= (int)$idEquipo ?>;

        // Validamos que tenga URL y que NO tenga la clase 'ya-cargado'
        if (!contenedor.hasClass('ya-cargado') && urlDestino) {

            $.ajax({
                url: urlDestino,
                type: 'GET',
                data: { idCarpeta: idCarpetaActual, idEquipo: idEquipoActual }, // <-- ¡ESTA ES LA MAGIA!
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

<script src="../dist/js/datatables-config.js"></script>
<!-- Cargas el archivo externo que ahora SOLO tiene el submit -->
<script src="../sdo-includes/equipamiento_js/equipamiento_estado.js"></script>
</body>
</html>