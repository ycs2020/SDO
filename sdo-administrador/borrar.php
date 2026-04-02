<?php
$listado = '../sdo-administrador/archivos-trabajos-generales/archivos-adjuntos-tg/';
//foreach ($listado as $elemento) {
//    echo $elemento . '<br/>';
//    unlink($elemento);
//
//}

    $dir = opendir($listado);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $listado . '/' . $file;
            if ( is_dir($full) ) {
                rmdir($full);
            }
            else {
                unlink($full);
            }
        }

        echo $file.'<br>';
    }
    closedir($dir);
    rmdir($listado);


?>