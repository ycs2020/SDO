<?php
$archivoDoc  = 'worddoc.doc';
$archivoDocx = 'worddocx2.docx';

function convertirWordAHTML($rutaArchivo) {

    $rutaAbsoluta = realpath($rutaArchivo);
    if (!$rutaAbsoluta) {
        return "❌ Archivo no encontrado: $rutaArchivo";
    }

    $libreoffice = '/usr/bin/libreoffice';
    $outputDir   = dirname($rutaAbsoluta);
    $nombreBase  = pathinfo($rutaArchivo, PATHINFO_FILENAME);
    $archivoHtml = $outputDir . '/' . $nombreBase . '.html';

    // Eliminar HTML previo si existe
    if (file_exists($archivoHtml)) {
        unlink($archivoHtml);
    }

    // 🔐 Forzar entorno HOME para www-data
    $cmd = "HOME=/tmp $libreoffice --headless --convert-to html "
        . escapeshellarg($rutaAbsoluta)
        . " --outdir "
        . escapeshellarg($outputDir)
        . " 2>&1";

    $output = shell_exec($cmd);

// 🧹 Limpiar advertencias relacionadas con Java
    $output = preg_replace('/^.*java.*?function.*?correctly.*$\n?/mi', '', $output);
    $output = preg_replace('/^.*javaldx.*$\n?/mi', '', $output);

    // Debug visual
    $debug  = "<b>📂 Archivo original:</b> $rutaAbsoluta<br>";
    $debug .= "<b>🔧 Comando ejecutado:</b><br><code>$cmd</code><br><br>";
    $debug .= "<b>📤 Salida LibreOffice:</b><pre>$output</pre><br>";

    if (!file_exists($archivoHtml)) {
        $debug .= "<span style='color:red;font-weight:bold;'>❌ ERROR: LibreOffice no generó el archivo HTML</span>";
        return $debug;
    }

    $html = file_get_contents($archivoHtml);

    return $debug . "<hr><b>✅ HTML generado correctamente:</b><br><div style='border:1px solid #ccc;padding:15px;background:#fafafa;'>$html</div>";
}

// Procesar formulario
$resultadoHTML = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['convert_doc'])) {
        $resultadoHTML = convertirWordAHTML($archivoDoc);
    }

    if (isset($_POST['convert_docx'])) {
        $resultadoHTML = convertirWordAHTML($archivoDocx);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visor Word con formato (LibreOffice)</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 30px;">

<h2>📝 Visor Word con formato real (LibreOffice)</h2>

<form method="POST">
    <button type="submit" name="convert_doc">📄 Ver Word DOC con formato</button>
    <button type="submit" name="convert_docx">📄 Ver Word DOCX con formato</button>
</form>

<hr>

<?= $resultadoHTML ?: '<b>Seleccione un archivo para visualizarlo</b>' ?>

</body>
</html>
