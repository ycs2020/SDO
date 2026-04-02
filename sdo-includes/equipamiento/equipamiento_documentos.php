<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

function formatearVacioFilas($valor)
{
    $valorLimpio = trim($valor ?? '');
    return $valorLimpio !== '' ? $valorLimpio : '-';
}

function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function normalizarRutaWeb($ruta)
{
    $ruta = str_replace('\\', '/', trim((string)$ruta));
    $ruta = preg_replace('#/+#', '/', $ruta);

    if ($ruta === '') {
        return '';
    }

    if ($ruta[0] !== '/') {
        $ruta = '/' . $ruta;
    }

    return $ruta;
}

function obtenerRutaFisicaDesdeWeb($rutaWeb)
{
    if (empty($rutaWeb)) {
        return '';
    }

    $documentRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
    $rutaWeb = str_replace('\\', '/', trim($rutaWeb));

    // Quitar espacios y barras duplicadas
    $rutaWeb = preg_replace('#/+#', '/', $rutaWeb);

    // Si la ruta ya es absoluta del sistema, devolverla normalizada
    if (strpos($rutaWeb, $documentRoot) === 0) {
        return $rutaWeb;
    }

    // Quitar ../ y ./ al inicio o en medio
    $partes = explode('/', $rutaWeb);
    $seguras = [];

    foreach ($partes as $parte) {
        if ($parte === '' || $parte === '.') {
            continue;
        }

        if ($parte === '..') {
            array_pop($seguras);
            continue;
        }

        $seguras[] = $parte;
    }

    return $documentRoot . '/' . implode('/', $seguras);
}

function obtenerExtension($rutaArchivo)
{
    return strtolower(pathinfo((string)$rutaArchivo, PATHINFO_EXTENSION));
}

function obtenerIconoArchivo($extension)
{
    switch ($extension) {
        case 'pdf':
            return 'fas fa-file-pdf text-danger';
        case 'doc':
        case 'docx':
            return 'fas fa-file-word text-primary';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
            return 'fas fa-file-image text-success';
        default:
            return 'fas fa-file-alt text-secondary';
    }
}

function convertirOfficeAPdf($rutaFisicaOriginal, $rutaWebOriginal)
{
    if (!file_exists($rutaFisicaOriginal)) {
        return [
            'ok' => false,
            'mensaje' => 'El archivo original no existe.',
            'rutaPdfWeb' => '',
            'rutaPdfFisica' => ''
        ];
    }

    $extension = obtenerExtension($rutaFisicaOriginal);
    if (!in_array($extension, ['doc', 'docx'], true)) {
        return [
            'ok' => false,
            'mensaje' => 'Tipo de archivo no convertible.',
            'rutaPdfWeb' => '',
            'rutaPdfFisica' => ''
        ];
    }

    $directorioFisico = dirname($rutaFisicaOriginal);
    $nombreBase = pathinfo($rutaFisicaOriginal, PATHINFO_FILENAME);

    $directorioConvertidosFisico = $directorioFisico . '/convertidos_pdf';
    if (!is_dir($directorioConvertidosFisico)) {
        if (!mkdir($directorioConvertidosFisico, 0775, true) && !is_dir($directorioConvertidosFisico)) {
            return [
                'ok' => false,
                'mensaje' => 'No fue posible crear el directorio de PDFs convertidos.',
                'rutaPdfWeb' => '',
                'rutaPdfFisica' => ''
            ];
        }
    }

    $nombrePdf = $nombreBase . '.pdf';
    $rutaPdfFisica = $directorioConvertidosFisico . '/' . $nombrePdf;

    $rutaWebOriginal = normalizarRutaWeb($rutaWebOriginal);
    $directorioWebOriginal = dirname($rutaWebOriginal);
    $rutaPdfWeb = normalizarRutaWeb($directorioWebOriginal . '/convertidos_pdf/' . $nombrePdf);

    $requiereConversion = true;

    if (file_exists($rutaPdfFisica)) {
        $mtimeOriginal = @filemtime($rutaFisicaOriginal);
        $mtimePdf = @filemtime($rutaPdfFisica);

        if ($mtimePdf !== false && $mtimeOriginal !== false && $mtimePdf >= $mtimeOriginal) {
            $requiereConversion = false;
        }
    }

    if ($requiereConversion) {
        $comando = 'libreoffice --headless --convert-to pdf --outdir '
            . escapeshellarg($directorioConvertidosFisico) . ' '
            . escapeshellarg($rutaFisicaOriginal) . ' 2>&1';

        exec($comando, $salida, $codigoRetorno);

        clearstatcache(true, $rutaPdfFisica);

        if ($codigoRetorno !== 0 || !file_exists($rutaPdfFisica)) {
            return [
                'ok' => false,
                'mensaje' => 'No se pudo convertir el archivo con LibreOffice.',
                'rutaPdfWeb' => '',
                'rutaPdfFisica' => ''
            ];
        }
    }

    return [
        'ok' => true,
        'mensaje' => 'Conversión realizada correctamente.',
        'rutaPdfWeb' => $rutaPdfWeb,
        'rutaPdfFisica' => $rutaPdfFisica
    ];
}

$idCarpeta = isset($_GET['idCarpeta']) ? (int)$_GET['idCarpeta'] : 0;
$idArchivoSeleccionado = isset($_GET['idArchivo']) ? (int)$_GET['idArchivo'] : 0;

if ($idCarpeta <= 0) {
    echo '<div class="alert alert-warning">Categoría no válida.</div>';
    exit;
}

$sqlArchivos = "SELECT * 
                FROM archivos_equipamiento_hbqp 
                WHERE idCategoria = $idCarpeta
                ORDER BY id ASC";

$resArchivos = mysqli_query($conexion, $sqlArchivos);

if (!$resArchivos || mysqli_num_rows($resArchivos) <= 0) {
    echo '<div class="alert alert-warning">No existen archivos asociados a esta categoría.</div>';
    exit;
}

$archivos = [];
while ($rowArchivos = mysqli_fetch_assoc($resArchivos)) {
    $archivos[] = $rowArchivos;
}

if ($idArchivoSeleccionado <= 0 && !empty($archivos)) {
    $idArchivoSeleccionado = (int)$archivos[0]['id'];
}

$archivoActivo = null;
foreach ($archivos as $archivo) {
    if ((int)$archivo['id'] === $idArchivoSeleccionado) {
        $archivoActivo = $archivo;
        break;
    }
}

if ($archivoActivo === null && !empty($archivos)) {
    $archivoActivo = $archivos[0];
    $idArchivoSeleccionado = (int)$archivoActivo['id'];
}

$visorHtml = '<div class="alert alert-secondary">Seleccione un archivo para visualizar.</div>';
$accionesHtml = '';

if ($archivoActivo !== null) {
    $nomArchivoActivo = $archivoActivo['nomArchivo'];
    $rutaArchivoActiva = $archivoActivo['rutaArchivo'];

    $rutaWebActiva = normalizarRutaWeb($rutaArchivoActiva);
    $rutaFisicaActiva = obtenerRutaFisicaDesdeWeb($rutaWebActiva);
    $extensionActiva = obtenerExtension($rutaArchivoActiva);

    if ($rutaWebActiva === '' || $rutaFisicaActiva === '' || !file_exists($rutaFisicaActiva)) {
//        $visorHtml = '<div class="alert alert-danger">El archivo seleccionado no existe físicamente en el servidor.</div>';
        $visorHtml = '<div class="alert alert-danger">'.$rutaFisicaActiva.'</div>';
    } else {
        $accionesHtml = '
            <div class="mb-3 text-right">
                <a class="btn btn-sm btn-primary mr-2" href="' . h($rutaWebActiva) . '" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Abrir
                </a>
                <a class="btn btn-sm btn-success" href="' . h($rutaWebActiva) . '" download>
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        ';

        if ($extensionActiva === 'pdf') {
            $visorHtml = '
                <iframe src="' . h($rutaWebActiva) . '" class="visor-frame"></iframe>
            ';
        } elseif (in_array($extensionActiva, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $visorHtml = '
                <div class="text-center">
                    <img src="' . h($rutaWebActiva) . '" class="img-fluid visor-imagen" alt="' . h($nomArchivoActivo) . '">
                </div>
            ';
        } elseif (in_array($extensionActiva, ['doc', 'docx'], true)) {
            $conversion = convertirOfficeAPdf($rutaFisicaActiva, $rutaWebActiva);

            if ($conversion['ok']) {
                $rutaPdfWeb = $conversion['rutaPdfWeb'];

                $accionesHtml = '
                    <div class="mb-3 text-right">
                        <a class="btn btn-sm btn-primary mr-2" href="' . h($rutaPdfWeb) . '" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Abrir PDF
                        </a>
                        <a class="btn btn-sm btn-success mr-2" href="' . h($rutaPdfWeb) . '" download>
                            <i class="fas fa-download"></i> Descargar PDF
                        </a>
                        <a class="btn btn-sm btn-secondary" href="' . h($rutaWebActiva) . '" download>
                            <i class="fas fa-file-word"></i> Descargar original
                        </a>
                    </div>
                ';

                $visorHtml = '
                    <iframe src="' . h($rutaPdfWeb) . '" class="visor-frame"></iframe>
                ';
            } else {
                $visorHtml = '
                    <div class="alert alert-warning">
                        No fue posible convertir el documento Word a PDF.<br>
                        <strong>Detalle:</strong> ' . h($conversion['mensaje']) . '
                    </div>
                ';
            }
        } else {
            $visorHtml = '
                <div class="alert alert-info">
                    Este tipo de archivo no tiene vista previa disponible.<br>
                    Puede descargarlo para revisarlo.
                </div>
            ';
        }
    }
}
?>

<style>
    .border-col {
        border-right: 1px solid #dee2e6;
    }

    .archivo-item {
        display: block;
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 8px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: #fff;
        color: #343a40;
        text-decoration: none !important;
        transition: all .2s ease-in-out;
    }

    .archivo-item:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    .archivo-item.activo {
        background: #007bff;
        border-color: #007bff;
        color: #fff !important;
    }

    .archivo-item .nombre-archivo {
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }

    .archivo-item .tipo-archivo {
        font-size: 12px;
        opacity: .85;
    }

    .visor-frame {
        width: 100%;
        height: 75vh;
        min-height: 650px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: #fff;
    }

    .visor-imagen {
        max-height: 75vh;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: #fff;
        padding: 10px;
    }

    .contenedor-listado {
        max-height: 80vh;
        overflow-y: auto;
        padding-right: 5px;
    }

    @media (max-width: 767.98px) {
        .border-col {
            border-right: 0;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 15px;
            padding-bottom: 15px;
        }

        .visor-frame {
            min-height: 500px;
        }
    }
</style>

<div class="container-fluid">
    <h5 class="text-center font-weight-bold">Documentaci&oacute;n</h5>
    <hr>

    <div class="row" id="contenedorDocumentos">
        <div class="col-xl-3 col-lg-4 col-md-4 col-12 border-col">
            <h6 class="text-center font-weight-bold">Listado</h6>
            <hr>

            <div class="contenedor-listado">
                <?php foreach ($archivos as $rowArchivos): ?>
                    <?php
                    $idArchivo = (int)$rowArchivos['id'];
                    $nomArchivo = $rowArchivos['nomArchivo'];
                    $rutaArchivo = $rowArchivos['rutaArchivo'];
                    $extension = obtenerExtension($rutaArchivo);
                    $icono = obtenerIconoArchivo($extension);
                    $activo = ($idArchivo === $idArchivoSeleccionado) ? 'activo' : '';
                    ?>
                    <a href="#"
                       class="archivo-item <?php echo $activo; ?> btnSeleccionarArchivo"
                       data-idcarpeta="<?php echo (int)$idCarpeta; ?>"
                       data-idarchivo="<?php echo $idArchivo; ?>">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <i class="<?php echo h($icono); ?>"></i>
                            </div>
                            <div>
                                <div class="nombre-archivo"><?php echo h(formatearVacioFilas($nomArchivo)); ?></div>
                                <div class="tipo-archivo"><?php echo strtoupper(h($extension)); ?></div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8 col-md-8 col-12" id="contenedorVisorArchivo">
            <h6 class="text-center font-weight-bold">Visor</h6>
            <hr>

            <?php if ($archivoActivo !== null): ?>
                <div class="mb-2">
                    <strong>Archivo seleccionado:</strong>
                    <?php echo h(formatearVacioFilas($archivoActivo['nomArchivo'])); ?>
                </div>
            <?php endif; ?>

            <?php echo $accionesHtml; ?>
            <?php echo $visorHtml; ?>
        </div>

        <script>
            $(document).on('click', '.btnSeleccionarArchivo', function(e){
                e.preventDefault();

                let idCarpeta = $(this).data('idcarpeta');
                let idArchivo = $(this).data('idarchivo');
                let $item = $(this);

                $('.archivo-item').removeClass('activo');
                $item.addClass('activo');

                $('#contenedorVisorArchivo').html(`
        <h6 class="text-center font-weight-bold">Visor</h6>
        <hr>
        <div class="text-center p-4">
            <i class="fas fa-spinner fa-spin"></i> Cargando archivo...
        </div>
    `);

                $.ajax({
                    url: '/sdo-includes/equipamiento/visor_documento_ajax.php',
                    type: 'GET',
                    data: {
                        idCarpeta: idCarpeta,
                        idArchivo: idArchivo
                    },
                    success: function(response){
                        $('#contenedorVisorArchivo').html(response);
                    },
                    error: function(xhr, status, error){
                        console.log('STATUS:', status);
                        console.log('ERROR:', error);
                        console.log('RESPONSE TEXT:', xhr.responseText);

                        $('#contenedorVisorArchivo').html(`
                <div class="alert alert-danger">
                    <strong>Ocurrió un error al cargar el visor.</strong><br>
                    Status: ${xhr.status}<br>
                    Error: ${error}
                </div>
            `);
                    }
                });
            });
        </script>
    </div>
</div>