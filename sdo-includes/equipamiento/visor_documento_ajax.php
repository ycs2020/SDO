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
    $rutaWeb = preg_replace('#/+#', '/', $rutaWeb);

    if (strpos($rutaWeb, $documentRoot) === 0) {
        return $rutaWeb;
    }

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

function convertirOfficeAPdf($rutaFisicaOriginal, $rutaWebOriginal)
{
    if (!file_exists($rutaFisicaOriginal)) {
        return [
            'ok' => false,
            'mensaje' => 'El archivo original no existe.',
            'rutaPdfWeb' => ''
        ];
    }

    $extension = obtenerExtension($rutaFisicaOriginal);
    if (!in_array($extension, ['doc', 'docx'], true)) {
        return [
            'ok' => false,
            'mensaje' => 'Tipo de archivo no convertible.',
            'rutaPdfWeb' => ''
        ];
    }

    $documentRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
    $nombreBase = pathinfo($rutaFisicaOriginal, PATHINFO_FILENAME);

    $directorioConvertidosFisico = $documentRoot . '/uploads/convertidos_pdf';
    $directorioConvertidosWeb = '/uploads/convertidos_pdf';

    if (!is_dir($directorioConvertidosFisico)) {
        return [
            'ok' => false,
            'mensaje' => 'No existe el directorio de PDFs convertidos: ' . $directorioConvertidosFisico,
            'rutaPdfWeb' => ''
        ];
    }

    if (!is_writable($directorioConvertidosFisico)) {
        return [
            'ok' => false,
            'mensaje' => 'El directorio de PDFs convertidos no tiene permisos de escritura: ' . $directorioConvertidosFisico,
            'rutaPdfWeb' => ''
        ];
    }

    $hashArchivo = md5($rutaFisicaOriginal);
    $nombrePdfFinal = $nombreBase . '_' . $hashArchivo . '.pdf';

    $rutaPdfFisicaFinal = $directorioConvertidosFisico . '/' . $nombrePdfFinal;
    $rutaPdfWebFinal = $directorioConvertidosWeb . '/' . $nombrePdfFinal;

    $requiereConversion = true;

    if (file_exists($rutaPdfFisicaFinal)) {
        $mtimeOriginal = @filemtime($rutaFisicaOriginal);
        $mtimePdf = @filemtime($rutaPdfFisicaFinal);

        if ($mtimePdf !== false && $mtimeOriginal !== false && $mtimePdf >= $mtimeOriginal) {
            $requiereConversion = false;
        }
    }

    if ($requiereConversion) {
        $binarioLibreOffice = '/usr/bin/libreoffice';
        $perfilLibreOffice = '/tmp/libreoffice_sdo_profile';

        if (!file_exists($binarioLibreOffice)) {
            return [
                'ok' => false,
                'mensaje' => 'No se encontró LibreOffice en: ' . $binarioLibreOffice,
                'rutaPdfWeb' => ''
            ];
        }

        if (!is_dir($perfilLibreOffice)) {
            @mkdir($perfilLibreOffice, 0777, true);
        }

        if (!is_dir($perfilLibreOffice)) {
            return [
                'ok' => false,
                'mensaje' => 'No se pudo crear el perfil temporal de LibreOffice en: ' . $perfilLibreOffice,
                'rutaPdfWeb' => ''
            ];
        }

        $rutaPdfFisicaTemporal = $directorioConvertidosFisico . '/' . $nombreBase . '.pdf';

        if (file_exists($rutaPdfFisicaTemporal)) {
            @unlink($rutaPdfFisicaTemporal);
        }

        $salida = [];
        $codigoRetorno = 0;

        $comando = 'export HOME=/tmp; '
            . 'export XDG_CACHE_HOME=/tmp; '
            . 'export XDG_CONFIG_HOME=/tmp; '
            . escapeshellcmd($binarioLibreOffice)
            . ' --headless'
            . ' -env:UserInstallation=file://' . $perfilLibreOffice
            . ' --convert-to pdf'
            . ' --outdir ' . escapeshellarg($directorioConvertidosFisico)
            . ' ' . escapeshellarg($rutaFisicaOriginal)
            . ' 2>&1';

        exec($comando, $salida, $codigoRetorno);

        clearstatcache();

        if ($codigoRetorno !== 0) {
            return [
                'ok' => false,
                'mensaje' => 'No se pudo convertir el archivo con LibreOffice. Código: ' . $codigoRetorno . ' | Salida: ' . implode(' || ', $salida),
                'rutaPdfWeb' => ''
            ];
        }

        if (!file_exists($rutaPdfFisicaTemporal)) {
            return [
                'ok' => false,
                'mensaje' => 'LibreOffice informó conversión correcta, pero no se encontró el PDF temporal generado: ' . $rutaPdfFisicaTemporal . ' | Salida: ' . implode(' || ', $salida),
                'rutaPdfWeb' => ''
            ];
        }

        if (file_exists($rutaPdfFisicaFinal)) {
            @unlink($rutaPdfFisicaFinal);
        }

        if (!@rename($rutaPdfFisicaTemporal, $rutaPdfFisicaFinal)) {
            return [
                'ok' => false,
                'mensaje' => 'El PDF fue convertido, pero no se pudo renombrar a: ' . $rutaPdfFisicaFinal,
                'rutaPdfWeb' => ''
            ];
        }

        clearstatcache(true, $rutaPdfFisicaFinal);

        if (!file_exists($rutaPdfFisicaFinal)) {
            return [
                'ok' => false,
                'mensaje' => 'El PDF final no existe después del renombrado: ' . $rutaPdfFisicaFinal,
                'rutaPdfWeb' => ''
            ];
        }
    }

    return [
        'ok' => true,
        'mensaje' => 'Conversión realizada correctamente.',
        'rutaPdfWeb' => $rutaPdfWebFinal
    ];
}

$idCarpeta = isset($_GET['idCarpeta']) ? (int)$_GET['idCarpeta'] : 0;
$idArchivo = isset($_GET['idArchivo']) ? (int)$_GET['idArchivo'] : 0;

if ($idCarpeta <= 0 || $idArchivo <= 0) {
    echo '<div class="alert alert-warning">Parámetros inválidos.</div>';
    exit;
}

$sqlArchivo = "SELECT * 
               FROM archivos_equipamiento_hbqp 
               WHERE idCategoria = $idCarpeta AND id = $idArchivo
               LIMIT 1";

$resArchivo = mysqli_query($conexion, $sqlArchivo);

if (!$resArchivo || mysqli_num_rows($resArchivo) <= 0) {
    echo '<div class="alert alert-warning">No se encontró el archivo.</div>';
    exit;
}

$archivoActivo = mysqli_fetch_assoc($resArchivo);
$nomArchivoActivo = $archivoActivo['nomArchivo'];
$rutaArchivoActiva = $archivoActivo['rutaArchivo'];

$rutaWebActiva = normalizarRutaWeb($rutaArchivoActiva);
$rutaFisicaActiva = obtenerRutaFisicaDesdeWeb($rutaWebActiva);
$extensionActiva = obtenerExtension($rutaArchivoActiva);

echo '<h6 class="text-center font-weight-bold">Visor</h6>';
echo '<hr>';
echo '<div class="mb-2"><strong>Archivo seleccionado:</strong> ' . h(formatearVacioFilas($nomArchivoActivo)) . '</div>';

if ($rutaWebActiva === '' || $rutaFisicaActiva === '' || !file_exists($rutaFisicaActiva)) {
    echo '<div class="alert alert-danger">El archivo no existe físicamente en el servidor.<br>' . h($rutaFisicaActiva) . '</div>';
    exit;
}

if ($extensionActiva === 'pdf') {
    echo '
    <div class="mb-3 text-right">
        <a class="btn btn-sm btn-primary mr-2" href="' . h($rutaWebActiva) . '" target="_blank">Abrir</a>
        <a class="btn btn-sm btn-success" href="' . h($rutaWebActiva) . '" download>Descargar</a>
    </div>
    <iframe src="' . h($rutaWebActiva) . '" class="visor-frame"></iframe>';
} elseif (in_array($extensionActiva, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
    echo '
    <div class="mb-3 text-right">
        <a class="btn btn-sm btn-primary mr-2" href="' . h($rutaWebActiva) . '" target="_blank">Abrir</a>
        <a class="btn btn-sm btn-success" href="' . h($rutaWebActiva) . '" download>Descargar</a>
    </div>
    <div class="text-center">
        <img src="' . h($rutaWebActiva) . '" class="img-fluid visor-imagen" alt="' . h($nomArchivoActivo) . '">
    </div>';
} elseif (in_array($extensionActiva, ['doc', 'docx'], true)) {
    $conversion = convertirOfficeAPdf($rutaFisicaActiva, $rutaWebActiva);

    if ($conversion['ok']) {
        $rutaPdfWeb = $conversion['rutaPdfWeb'];

        echo '
        <div class="mb-3 text-right">
            <a class="btn btn-sm btn-primary mr-2" href="' . h($rutaPdfWeb) . '" target="_blank">Abrir PDF</a>
            <a class="btn btn-sm btn-success mr-2" href="' . h($rutaPdfWeb) . '" download>Descargar PDF</a>
            <a class="btn btn-sm btn-secondary" href="' . h($rutaWebActiva) . '" download>Descargar original</a>
        </div>
        <iframe src="' . h($rutaPdfWeb) . '" class="visor-frame"></iframe>';
    } else {
        echo '<div class="alert alert-warning">No fue posible convertir el documento Word a PDF.<br><strong>Detalle:</strong> ' . h($conversion['mensaje']) . '</div>';
    }
} else {
    echo '<div class="alert alert-info">Este tipo de archivo no tiene vista previa disponible.</div>';
}