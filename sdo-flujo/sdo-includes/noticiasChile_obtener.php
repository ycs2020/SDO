<?php
// Cargar el XML desde la URL proporcionada
//$xml_url = 'https://geoteck.cl/rss/index2.php';
$xml_url = 'https://geoteck.cl/rss/index_terra.php';
$xml = simplexml_load_file($xml_url);

// Función para convertir el número del mes a su nombre en español
function obtenerMesEnEspanol($mes)
{
    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    return $meses[$mes] ?? 'mes desconocido';
}

$noticias = [];

if ($xml !== false) {
    foreach ($xml->noticia as $noticia) {
        // Obtener los valores necesarios o usar valores predeterminados
        $srcNoticia = !empty($noticia->imagen) ? htmlspecialchars($noticia->imagen) : 'latercera.png';
        $tituloNoticia = !empty($noticia->titulo) ? htmlspecialchars($noticia->titulo) : 'La Tercera';
        $enlaceNoticia = !empty($noticia->enlace) ?  (string)$noticia->enlace : '#';

        // Formatear la fecha
        $fecha_original = (string)$noticia->fecha;
        $fecha_obj = DateTime::createFromFormat('d/m/Y H:i', $fecha_original);

        if ($fecha_obj) {
            $dia = $fecha_obj->format('d');
            $mes = obtenerMesEnEspanol((int)$fecha_obj->format('m'));
            $anio = $fecha_obj->format('Y');
            $hora = $fecha_obj->format('H:i');
            $fecha_formateada = "$dia de $mes del $anio a las $hora hrs";
        } else {
            $fecha_formateada = 'Fecha no disponible';
        }

        // Agregar noticia al array
        $noticias[] = [
            'imagen' => $srcNoticia,
            'titulo' => $tituloNoticia,
            'enlace' => $enlaceNoticia,
            'fecha' => $fecha_formateada
        ];
    }
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($noticias);
