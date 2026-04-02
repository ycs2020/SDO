<?php
// Cargar el XML desde la URL proporcionada
$xml_url = 'https://geoteck.cl/rss/index2.php';
$xml = simplexml_load_file($xml_url);

if ($xml === false) {
    echo '<p>Error al cargar el RSS.</p>';
    exit;
}

// Iniciar la estructura del HTML
echo '<!DOCTYPE html>';
echo '<html lang="es">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
echo '<title>Noticias RSS</title>';
echo '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">';
echo '<style>';
echo '.widget-user-2 { margin-bottom: 20px; }';
echo '.table-custom img { max-width: 50px; margin-right: 10px; vertical-align: middle; }';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<div class="container">';
echo '<div class="row">'; // Inicio de fila


echo '<div class="col-md-6 col-12">'; // Tamaño ajustado
echo '<div class="card card-widget widget-user-2">'; // Card widget
echo '<div class="card-header bg-primary text-center">';
echo '<h3 class="card-title">Noticias Chile</h3>'; // Título general
echo '</div>';
echo '<div class="card-footer p-0">';

// Fecha de la noticia
echo '<h5 class="m-2 text-center" style="font-size: 11pt;">Publicado el ' . htmlspecialchars($noticia->fecha) . '</h5>';

echo '<div id="scrollableTable" style="height: 500px; overflow-y: scroll;">';
echo '<table class="table table-striped table-custom" id="sismos" border="1" cellpadding="3" style="width: 100%; font-size: 11pt; background-color: #e8effd">';

// Procesar cada noticia en el XML
foreach ($xml->noticia as $noticia) {
    echo '<tr>';
    if (!empty($noticia->imagen)) {
        echo '<td><img src="' . htmlspecialchars($noticia->imagen) . '" alt="' . htmlspecialchars($noticia->titulo) . '"></td>'; // Imagen pequeña
    } else {
        echo '<td><img src="latercera.png" alt="La Tercera"></td>'; // Imagen por defecto
    }
    echo '<td>' . htmlspecialchars($noticia->titulo) . '</td>'; // Título de la noticia
    echo '</tr>';

}

echo '</table>';
echo '</div>'; // Fin de scrollableTable
echo '</div>'; // Fin de card-footer
echo '</div>'; // Fin de card
echo '</div>'; // Fin de col-md-6
echo '</div>'; // Fin de fila
echo '</div>'; // Fin de container

// Incluir Bootstrap JS y dependencias
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>';
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>';
echo '</body>';
echo '</html>';
?>
