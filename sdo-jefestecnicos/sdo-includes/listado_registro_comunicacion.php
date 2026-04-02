<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM `registro_general_comunicacion` ORDER BY fecha DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

$nomUsuarioBuscar = '';

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

// Procesar los resultados
while ($row = $result->fetch_assoc()) {
    $id = $row["id"];
    $fecha = $row["fecha"];
    $responsable = $row["responsables"];
    $turno = $row['turno'];

    $sqlRegistro = "SELECT COUNT(*) as cantidad FROM `visado_registro_comunicacion` WHERE nomVisado LIKE('%$nomUsuarioBuscar%') AND idRegistro = $id";
    $resRegistro = mysqli_query($conexion,$sqlRegistro);
    while($rowRegistro = $resRegistro->fetch_assoc()){
        $cantidad = $rowRegistro['cantidad'];
        $row['cantidad'] = $cantidad;
    }


    if (empty($fecha)) {
        $fechaCreacion = 'Sin Información';
    } else {
        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fecha)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fecha)));

        $fechaCreacion = $inicioFecha . '<br>' . $inicioHora . ' hrs.';
    }

    $nombres = explode(" - ", $responsable);
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
    $row['cantidad'] = $cantidad;
    $row['id'] = $id;
    $row['fecha'] = $fechaCreacion;
    $row['responsables'] = $responsable;
    $row['turno'] = $turno;
    $row['aplicaModificar'] = $aplica;

    $datos[] = $row;
}

// Cerrar el statement y la conexión
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
header('Content-Type: application/json');
echo json_encode(array('data' => $datos));
?>
