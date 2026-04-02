<?php


$listado = glob('../sdo-imagenes-orden/*');
foreach ($listado as $elemento) {
    echo $elemento . '<br/><br>';
    rmdir($elemento);

    
}

?>

