<?php

include 'conexion.php';

$id = $_POST['id'];

$consulta = "SELECT nomCategoria,imagenCategoria,idDepartamento FROM categorias_equipos WHERE idCategoria = $id";
$res = mysqli_query($conexion, $consulta);

while ($row = $res->fetch_assoc()) {
    $datos = [];
    $datos['nomCategoria'] = $row['nomCategoria'];
    $datos['imagenCategoria'] = $row['imagenCategoria'];
    $datos['idDepartamento'] = $row['idDepartamento'];
}


echo json_encode($datos);
// echo '';

