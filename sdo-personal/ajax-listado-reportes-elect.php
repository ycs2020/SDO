<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$sql = "SELECT * FROM reportes_electricos_sdo ORDER BY fecha DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

$nomUsuarioBuscar = '';
$nomEncargadoTurno = '';

if(isset($_POST['nomUsuarioModificar'])){
    $nomUsuarioModificar = $_POST['nomUsuarioModificar'];
    if(empty($nomUsuarioModificar)){
        $nomUsuarioBuscar = 'No Aplica';
    }else{
        $nomUsuarioBuscar = $nomUsuarioModificar;
    }
}else{
    $nomUsuarioBuscar = 'No Aplica';
}

while ($row = $result->fetch_assoc()) {
    $fecha = $row["fecha"];
    $turno = $row["turno"];
    $tipoTurno = $row["tipoTurno"];
    $folio = $row["folio"];
    $id = $row["id"];

    $fecha_formateada = date("d-m-y", strtotime($fecha));


    $nombres = explode(" - ", $turno);

    $encontrado = false;
    foreach ($nombres as $nombre) {
        if (strpos($nombre, $nomUsuarioBuscar) !== false) {
            $encontrado = true;
            break;
        }
    }

    if ($encontrado) {
        $aplica = 'si';
    }else{
        $aplica = 'no';
    }

    $row['fecha'] = $fecha_formateada;
    $row['turno'] = $turno;
    $row['tipoTurno'] = $tipoTurno;
    $row['folio'] = $folio;
    $row['id'] = $id;
    $row['aplicaModificar'] = $aplica;

    $datos[] = $row;

}
// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);
?>
