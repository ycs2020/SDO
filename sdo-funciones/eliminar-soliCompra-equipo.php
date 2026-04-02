<?php 

include 'conexion.php';
// echo '';

if(isset($_POST['id'])){

    $id = $_POST['id'];

    $sql = "UPDATE solicitudes_compra_sdo  SET idCategoria = 0 WHERE idSoliCompra   = $id";
    $res = mysqli_query($conexion, $sql);
}