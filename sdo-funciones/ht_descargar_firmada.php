<?php

if (isset($_POST['archivo']) && isset($_POST['idOrden']) && isset($_POST['folio'])) {
    $rutaArchivo = '../sdo-hojaTec-firmadas/' . basename($_POST['archivo']);
    $folio = $_POST['folio'];
    $idOrden = (int)$_POST['idOrden'];

    if (file_exists($rutaArchivo)) {
        // Cambia el nombre según la orden
        $nuevoNombre = $folio . '.pdf';

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $nuevoNombre . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($rutaArchivo));
        readfile($rutaArchivo);
        exit;
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "Parámetros incompletos.";
}

