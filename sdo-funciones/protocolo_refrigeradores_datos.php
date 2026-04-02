<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM protocolo_refrigeradores ORDER BY fechaProtocolo DESC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $idProtocolo = $row["idProtocolo"];
    $fechaProtocolo = $row["fechaProtocolo"];
    $ejecutanteProtocolo = $row["ejecutanteProtocolo"];
    $folioProtocolo = $row["folioProtocolo"];
    $idEquipo = $row["idEquipo"];
    $estadoEquipo = $row["estadoEquipo"];
    $entornoEquipo = $row["entornoEquipo"];
    $tempEquipo = $row["tempEquipo"];
    $sonidoEquipo = $row["sonidoEquipo"];
    $luzEquipo = $row["luzEquipo"];
    $controlEquipo = $row["controlEquipo"];
    $observacionControl = $row["observacionControl"];
    $isAnulado = $row["isAnulado"];
    $fechaAnulado = $row["fechaAnulado"];
    $nombreAnulado = $row["nombreAnulado"];

    if(!empty($observacionControl)){
        $tieneObsControl = 1;
    }else{
        $tieneObsControl = 0;
    }

    $fechaTime = strtotime($fechaProtocolo);
    $fechaPalabra = fechaEspañol2($fechaTime);
    $horaPalabra = date('H:i',$fechaTime).' hrs.';

    $fechaCompleta = $fechaPalabra.'<br>'.$horaPalabra;

    if($idEquipo > 0){

        $nombreEquipo = getNomEquipamiento($idEquipo);
        $inventarioEquipo = getInventarioEquipamiento($idEquipo);

        $nombreCompleto = $nombreEquipo.'<br>( N° Inventario: '.$inventarioEquipo.' )';

    }else{
        $nombreEquipo = 'No Aplica';
    }

    $row["fechaProtocolo"] = $fechaProtocolo;
    $row["fechaProtocolo2"] = $fechaCompleta;

    $row["ejecutanteProtocolo"] = $ejecutanteProtocolo;
    $row["folioProtocolo"] = $folioProtocolo;
    $row["idEquipo"] = $idEquipo;
    $row["estadoEquipo"] = $estadoEquipo;
    $row["entornoEquipo"] = $entornoEquipo;
    $row["tempEquipo"] = $tempEquipo;
    $row["sonidoEquipo"] = $sonidoEquipo;
    $row["luzEquipo"] = $luzEquipo;
    $row["controlEquipo"] = $controlEquipo;
    $row["nombreEquipo"] = $nombreCompleto;
    $row["observacionControl"] = $observacionControl;
    $row["tieneObsControl"] = $tieneObsControl;
    $row["isAnulado"] = $isAnulado;
    $row["fechaAnulado"] = $fechaAnulado;
    $row["nombreAnulado"] = $nombreAnulado;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>