<?php

function normalizarNombre($nombre)
{
    // Mapeo de los valores incorrectos a los valores correctos
    $mapeo = array(
        'Energ$eda' => 'Energía',
        'ILUMINACI$d3N' => 'ILUMINACIÓN',
        'VIN$20Y$20VEX' => 'VIN Y VEX',
        'defaultAlarmClass' => 'Default Alarm Class',
        'UMA' => 'UMA',
        'Analizador$20de$20Red' => 'Analizador de Red',
        'Fan$20Coil' => 'Fan Coil',
        'CO2' => 'CO2',
        'Refrigeradores$20Farmacia' => 'Refrigeradores Farmacia',
        'VAV' => 'VAV',
        'Gases$20Cl$ednicos' => 'Gases Clínicos',
        'Vigas$20Frias' => 'Vigas Frías',
        'CW' => 'CW',
        'Sala$20de$20Aislam$2e$20y$20Pabellon' => 'Sala de Aislam. y Pabellon',
        'Incendio' => 'Incendio',
        'NoCritica' => 'NoCrítica',
        'Combustible$20' => 'Combustible',
        'Central$20Frio' => 'Central Frio',
        'Agua$20Potable' => 'Agua Potable',
        'Central$20Calor' => 'Central Calor',
        'Paneles$20Solares' => 'Paneles Solares',
        'RIEGO' => 'RIEGO',
        'Agua$20Servidas' => 'Agua Servidas',
        'UPS' => 'UPS',
        'Fuera$20de$20Servicio' => 'Fuera de Servicio',
        'Data$20Center' => 'Data Center',
        'Agua$20Lluvias' => 'Agua Lluvias',
        'SENTINA' => 'SENTINA',
        'Agua$20Tratada' => 'Agua Tratada',
        'prueba' => 'prueba'
    );

    // Verificar si el nombre existe en el mapeo y devolver el valor correcto
    return isset($mapeo[$nombre]) ? $mapeo[$nombre] : $nombre;
}

// Función para procesar el archivo CSV
function procesarCSV($rutaEntrada, $rutaSalida)
{
    if (($handle = fopen($rutaEntrada, 'r')) !== FALSE) {
        $salida = fopen($rutaSalida, 'w');

        // Leer el encabezado del archivo CSV
        $headers = fgetcsv($handle, 1000, ',');
        $indexAlarmClass = array_search('Alarm Class', $headers);

        if ($indexAlarmClass === FALSE) {
            echo "No se encontró la columna 'Alarm Class' en el archivo CSV.";
            fclose($handle);
            fclose($salida);
            return;
        }

        // Escribir el encabezado en el archivo de salida
        fputcsv($salida, $headers);

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Normalizar solo el valor de la columna "Alarm Class"
            if (isset($data[$indexAlarmClass])) {
                $data[$indexAlarmClass] = normalizarNombre($data[$indexAlarmClass]);
            }
            fputcsv($salida, $data);
        }

        fclose($handle);
        fclose($salida);
        echo "Archivo procesado y guardado en: $rutaSalida";
    } else {
        echo "No se pudo abrir el archivo de entrada.";
    }
}

// Uso de la función
$rutaEntrada = 'SOURCES.csv'; // Cambia esto a la ruta correcta de tu archivo de entrada
$rutaSalida = 'SOURCESNombres.csv'; // Cambia esto a la ruta donde quieres guardar el archivo de salida
procesarCSV($rutaEntrada, $rutaSalida);

