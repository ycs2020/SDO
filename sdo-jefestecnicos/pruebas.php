<?php
// 1. INICIALIZAR VARIABLES
$datos_agrupados = [];
$errores = [];
$archivos_procesados = [];

// 2. DETECTAR SI EL FORMULARIO FUE ENVIADO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivos_excel'])) {

    $cantidad_archivos = count($_FILES['archivos_excel']['name']);

    for ($i = 0; $i < $cantidad_archivos; $i++) {
        $error_archivo = $_FILES['archivos_excel']['error'][$i];

        if ($error_archivo === UPLOAD_ERR_OK) {
            $rutaTemporal = $_FILES['archivos_excel']['tmp_name'][$i];
            $nombre_archivo = $_FILES['archivos_excel']['name'][$i];
            $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));

            if ($extension === 'csv') {
                if (($handle = fopen($rutaTemporal, "r")) !== FALSE) {
                    $numeroFila = 1;
                    $archivos_procesados[] = $nombre_archivo;

                    while (($datos = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($numeroFila <= 4) {
                            $numeroFila++;
                            continue;
                        }

                        if (isset($datos[0]) && isset($datos[3])) {
                            $timestamp_texto = trim($datos[0]);
                            $valor = trim($datos[3]);

                            // Convertir la fecha de texto a segundos reales (número entero)
                            $epoch = strtotime($timestamp_texto);

                            // LÓGICA DE TOLERANCIA: Buscar si ya existe la misma hora, 1 seg antes o 1 seg después
                            $llave_asignada = $epoch; // Por defecto es la hora exacta

                            if (isset($datos_agrupados[$epoch])) {
                                $llave_asignada = $epoch;
                            } elseif (isset($datos_agrupados[$epoch - 1])) {
                                $llave_asignada = $epoch - 1;
                            } elseif (isset($datos_agrupados[$epoch + 1])) {
                                $llave_asignada = $epoch + 1;
                            }

                            // Si no existe ninguna de las 3 opciones, creamos el espacio nuevo
                            if (!isset($datos_agrupados[$llave_asignada])) {
                                $datos_agrupados[$llave_asignada] = [
                                    'texto_fecha' => $timestamp_texto, // Guardamos el texto original para mostrarlo
                                    'valores'     => []
                                ];
                            }

                            // Guardamos el valor en esa fila unificada
                            $datos_agrupados[$llave_asignada]['valores'][$nombre_archivo] = $valor;
                        }
                        $numeroFila++;
                    }
                    fclose($handle);
                } else {
                    $errores[] = "Error al abrir el archivo: $nombre_archivo.";
                }
            } else {
                $errores[] = "El archivo $nombre_archivo ignorado. Por favor, sube solo formatos .csv.";
            }
        } elseif ($error_archivo !== UPLOAD_ERR_NO_FILE) {
            $errores[] = "Error al subir uno de los archivos. Código: " . $error_archivo;
        }
    }

    // 3. ORDENAR LOS DATOS (Como las llaves ahora son números de tiempo, ksort los ordena cronológicamente perfecto)
    if (!empty($datos_agrupados)) {
        ksort($datos_agrupados);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combinar Datos de Caldera (Con Tolerancia)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f7f6; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .error { color: #d9534f; background-color: #f2dede; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        .success { color: #3c763d; background-color: #dff0d8; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #2c3e50; color: white; }
        td:first-child { text-align: left; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        form { margin-bottom: 20px; padding: 15px; border: 1px dashed #95a5a6; background: #ecf0f1; }
        button { background-color: #3498db; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 3px; font-size: 15px;}
        button:hover { background-color: #2980b9; }
        .sin-info { color: #999; font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <h2>Comparador de Temperaturas (Margen de &plusmn;1 seg)</h2>

    <?php if (!empty($errores)): ?>
        <?php foreach ($errores as $error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label><strong>Selecciona múltiples archivos (.csv):</strong></label><br>
        <small>Mantén presionada la tecla 'Ctrl' para seleccionar más de un archivo.</small><br><br>
        <input type="file" name="archivos_excel[]" accept=".csv" multiple required>
        <br><br>
        <button type="submit">Agrupar por Hora</button>
    </form>

    <?php if (!empty($datos_agrupados)): ?>
        <div class="success">
            <strong>Archivos combinados con éxito. Se aplicó sincronización de &plusmn;1 segundo.</strong>
        </div>

        <table>
            <thead>
            <tr>
                <th>Timestamp</th>
                <?php foreach ($archivos_procesados as $nombre_archivo): ?>
                    <th>Valor en: <br><small><?php echo htmlspecialchars($nombre_archivo); ?></small></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($datos_agrupados as $epoch => $datos_fila): ?>
                <tr>
                    <td><?php echo htmlspecialchars($datos_fila['texto_fecha']); ?></td>

                    <?php foreach ($archivos_procesados as $nombre_archivo): ?>
                        <td>
                            <?php
                            if (isset($datos_fila['valores'][$nombre_archivo]) && $datos_fila['valores'][$nombre_archivo] !== '') {
                                echo "<strong>" . htmlspecialchars($datos_fila['valores'][$nombre_archivo]) . "</strong>";
                            } else {
                                echo "<span class='sin-info'>Sin Información</span>";
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>