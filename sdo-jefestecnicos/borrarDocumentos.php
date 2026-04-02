<?php
$files = glob('../sdo-solicCompra/*'); //obtenemos todos los nombres de los ficheros

$cadena = 'SC-';

$num = 1;
foreach ($files as $file) {

    $cadena2 = $num.'SC-';
    $pos = strpos($file, $cadena2);

    if ($pos === false) {
        echo "La cadena '$cadena2' no fue encontrada en la cadena '$file'<br><br>";
    } else {
        echo "La cadena '$cadena2' fue encontrada en la cadena '$file'";
        echo " y existe en la posición $pos<br><br>";
        $archivos2 = $file.'/';


        
    }

    $num++;


    // if(is_file($file))
    // unlink($file); //elimino el fichero
}
