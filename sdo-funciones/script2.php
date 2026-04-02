<?php


$listado = glob('../1sdo-archivos-orden/*');

$ruta2 = '../1sdo-archivos-orden/';

// function listar_directorios_ruta($ruta)
// {
//     // abrir un directorio y listarlo recursivo
//     if (is_dir($ruta)) {
//         if ($dh = opendir($ruta)) {
//             while (($file = readdir($dh)) !== false) {
//                 //esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
//                 //mostraría tanto archivos como directorios
//                 //echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
//                 if (is_dir($ruta . $file) && $file != "." && $file != "..") {
//                     //solo si el archivo es un directorio, distinto que "." y ".."
//                     echo "<br>Directorio: $ruta$file";
//                     listar_directorios_ruta($ruta . $file . "/");
//                 }
//             }
//             closedir($dh);
//         }
//     } else
//         echo "<br>No es ruta valida";
// }

// echo listar_directorios_ruta(($ruta2));

function listadoDirectorio($directorio){    
    $listado = scandir($directorio);	    unset($listado[array_search('.', $listado, true)]);
    unset($listado[array_search('..', $listado, true)]);
    if (count($listado) < 1) {
        return;
    }
    foreach($listado as $elemento){
        if(!is_dir($directorio.'/'.$elemento)) {
            echo "<li>- <a href='$directorio/$elemento'>$elemento</a></li>";
            unlink($directorio.'/'.$elemento);
        }
        if(is_dir($directorio.'/'.$elemento)) {
            echo '<li class="open-dropdown">+ '.$elemento.'</li>';
            echo '<ul class="dropdown d-none">';
                listadoDirectorio($directorio.'/'.$elemento);
            echo '</ul>';
        }
    }
}
listadoDirectorio('../sdo-imagenes-orden/');