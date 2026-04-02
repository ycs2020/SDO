<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

$valorTotal = valorTotalViajeNuevoVamoos();
$valorTotalExtension = valorTotalExtensionVamoos();
$total = $valorTotal + $valorTotalExtension;

echo '$' . number_format($total, 0, ",", ".");
?>