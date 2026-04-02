<?php
$composerLockFile = __DIR__ . '/composer/installed.json';

if (file_exists($composerLockFile)) {
    $composerData = json_decode(file_get_contents($composerLockFile), true);

    // Composer 2 almacena los paquetes dentro de un array llamado 'packages'
    if (isset($composerData['packages'])) {
        $composerData = $composerData['packages'];
    }

    foreach ($composerData as $package) {
        if (isset($package['name']) && $package['name'] === 'dompdf/dompdf') {
            echo 'Dompdf versión: ' . ($package['version'] ?? 'Desconocida');
            exit;
        }
    }
    echo 'No se encontró la versión de Dompdf en installed.json';
} else {
    echo 'No se encontró el archivo installed.json';
}
?>
