<?php
// Inicializar cURL

include_once('../../sdo-funciones/funciones.php');
include_once('../../sdo-funciones/conexion.php');
date_default_timezone_set('America/Santiago');

$ch = curl_init();

// Establecer la URL
curl_setopt($ch, CURLOPT_URL, 'https://api.gael.cloud/general/public/sismos');

// Establecer la opción para recibir la respuesta como string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Establecer la cabecera de la solicitud
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json'
));

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($ch);

// Verificar si hubo algún error
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Decodificar la respuesta JSON
    $data = json_decode($response, true);

    // Verificar si la decodificación fue exitosa y los datos son un array
    if (is_array($data)) {
        // Iterar a través de los datos y cambiar el formato de la fecha
        foreach ($data as &$item) {
            if (isset($item['Fecha'])) {
                $originalDate = $item['Fecha'];
                // Convertir la fecha al nuevo formato deseado
                $newDate = strtotime($originalDate);
                $newDate = fechaEspañolMinimizado($newDate);


                $magnitud = $item['Magnitud'];

//                if (substr($magnitud, -2) == '.0') {
//                    $magnitud = rtrim($magnitud, '.0');
//                }

                // Asignar la nueva fecha al item
                $item['Fecha'] = $newDate;
                $item['Magnitud'] = $magnitud;
            }
        }
    }

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Cerrar cURL
curl_close($ch);
?>
