<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión global
include_once '../sdo-funciones/conexion.php';

// 3. Verificar conexión
if ($conexion->connect_errno) {
    die("Fallo al conectar a MySQL: " . $conexion->connect_error);
}

// 4. Consulta a la tabla
$sql = "SELECT imagenCategoria FROM categorias_equipos";
$result = $conexion->query($sql);

// 5. Crear array con rutas
$imagenes = [];

if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $imagenes[] = $fila['imagenCategoria'];
    }
} else {
    die("No se encontraron rutas en la tabla.");
}

// 6. Carpeta de destino
$carpetaDestino = "imgEquipos1/";

if (!file_exists($carpetaDestino)) {
    if (!mkdir($carpetaDestino, 0777, true) && !is_dir($carpetaDestino)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $carpetaDestino));
    }
}

// 7. Copiar imágenes
foreach ($imagenes as $ruta) {
    $nombreArchivo = basename($ruta);
    $rutaOrigen = realpath($ruta); // Intentar obtener ruta absoluta

    // Si realpath falla (porque la ruta es relativa o externa), usar la ruta tal cual
    if (!$rutaOrigen || !file_exists($rutaOrigen)) {
        $rutaOrigen = $ruta;
    }

    $rutaDestino = $carpetaDestino . $nombreArchivo;

    if (file_exists($rutaOrigen)) {
        if (copy($rutaOrigen, $rutaDestino)) {
            echo "✅ Copiado: $nombreArchivo<br>";
        } else {
            echo "❌ Error al copiar: $nombreArchivo<br>";
        }
    } else {
        echo "⚠️ No encontrado: $ruta<br>";
    }
}

// 8. Cerrar conexión
$conexion->close();
?>