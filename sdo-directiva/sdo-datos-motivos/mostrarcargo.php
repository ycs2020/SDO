<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';
if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $cadena = '';
    $sql = "SELECT cargoDesti FROM destinatario_memo WHERE nomDesti = '$nombre'";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $cargo = $row['cargoDesti'];
    }
    $cadena .= '<label for="exampleNomMotivo" style="font-size:15px">Cargo:</label>';
    $cadena .= '<div class="input-group mb-3">';
    $cadena .= '<div class="input-group-prepend">';
    $cadena .= '<span class="input-group-text"><i class="fas fa-user"></i></span>';
    $cadena .= '</div>';
    $cadena .= '<input value="'.$cargo.'" type="text" style="text-transform:uppercase;" readonly required class="form-control" id="cargoDestinatarioMemoSelect" name="cargoDestinatarioMemoSelect">';
    $cadena .= '</div>';
    echo $cadena;

}