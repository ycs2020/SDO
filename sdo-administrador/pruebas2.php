<?php
// Desactivar cualquier salida previa que pueda corromper el archivo XML
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 1. Cabeceras del sistema para forzar la descarga del archivo XML
header('Content-Type: text/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="configuracion_puertas_ccaa.xml"');

// 2. Ruta a tu conexión blindada de SDO
require_once dirname(__DIR__) . '/sdo-funciones/conexion.php';

if (!isset($conexion) && isset($db)) { $conexion = $db; }
if (!isset($conexion) && isset($con)) { $conexion = $con; }

if (!isset($conexion) || !($conexion instanceof mysqli)) {
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    exit;
}

try {
    // 3. Consulta limpia para tomar todos los registros ordenados por el ID único
    $sql = "SELECT idUnique, idDevice, idRegion, name, no, type 
            FROM puertas_ccaa_subir 
            ORDER BY idUnique ASC";

    $resultado = $conexion->query($sql);

    // 4. Estructura de cabecera XML
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<Channels>\n";

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            // Escapar caracteres problemáticos en el XML (como &, <, >, ", ') que traiga la columna name
            $name_escaped = htmlspecialchars($fila['name'], ENT_XML1, 'UTF-8');

            // Construcción exacta de la etiqueta usando sprintf
            echo sprintf(
                "  <Channel idUnique=\"%s\" idDevice=\"%s\" idRegion=\"%s\" name=\"%s\" no=\"%s\" type=\"%s\"/>\n",
                $fila['idUnique'],
                $fila['idDevice'],
                $fila['idRegion'],
                $name_escaped,
                $fila['no'],
                $fila['type']
            );
        }
        $resultado->free();
    }

    echo "</Channels>\n";
    exit;

} catch (Exception $e) {
    echo "";
}