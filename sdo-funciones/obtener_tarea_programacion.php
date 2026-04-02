<?php 

include 'conexion.php';


$id = $_GET['id'];

$sqlSelect = "SELECT * FROM programacion_equipamiento_hbqp WHERE idCategoria = $id";
$resSelect = mysqli_query($conexion, $sqlSelect);

while($row = $resSelect->fetch_assoc()){
    $data = [];
    $data['id'] = intval($row['id']);
    $data['name'] = $row['name'];
    $data['actualStart'] = $row['actualStart'];
    $data['actualEnd'] = $row['actualEnd'];
}


echo json_encode($data);