<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

$buscador = '';

if(isset($_POST['idSubEquipoBuscar'])){
    $idSubEquipoBuscar = $_POST['idSubEquipoBuscar'];

    if($idSubEquipoBuscar > 0){
        $pasa = 'si';
    }else{
        echo '';
        exit;
    }
}

// Consulta SQL
$sql = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = $idSubEquipoBuscar ORDER BY piso ASC, pma ASC, nombreEquipo ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {



    $idEquipo = $row['id'];
    $identificadorUnico = $row['identificadorUnico'];
    $nombreEquipo = $row['nombreEquipo'];
    $idServicio = $row['idServicio'];
    $recinto = $row['recinto'];
    $pma = $row['pma'];
    $piso = $row['piso'];
    $estadoEquipo = $row['estadoEquipo'];
    $modelo = $row['modelo'];
    $tipoMenuSubEquipo = $row['tipoMenu'];

    if ($recinto == '') {
        $recinto = '-';
    }

    if ($pma == '') {
        $pma = '-';
    }

    if ($piso == '') {
        $piso = '-';
    }

    $row['id'] = $idEquipo;
    $row['identificadorUnico'] = $identificadorUnico;
    $row['nombreEquipo'] = $nombreEquipo;
    $row['idServicio'] = $idServicio;
    $row['recinto'] = $recinto;
    $row['pma'] = $pma;
    $row['piso'] = $piso;
    $row['estadoEquipo'] = $estadoEquipo;
    $row['modelo'] = $modelo;
    $row['tipoMenu'] = $tipoMenuSubEquipo;
    $row['sqlsql'] = $sql;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>