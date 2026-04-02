<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM ACS_EventInfoTable WHERE (EventType BETWEEN 109 AND 111) AND (EventTime BETWEEN '2024-01-01 00:00:00' AND '2024-01-02 00:00:00') ORDER BY EventTime ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();


// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $id = $row["ID"];
    $row['ID'] = $id;

    $chnlUniqueID = $row["ChnlUniqueID"];
    $row['ChnlUniqueID'] = $chnlUniqueID;

    $jobNumber = $row["JobNumber"];
    $row['JobNumber'] = $jobNumber;

    $cardNumber = $row["CardNumber"];
    $row['CardNumber'] = $cardNumber;

    $eventType = $row["EventType"];
    $row['EventType'] = $eventType;

    $eventTime = $row["EventTime"];
    $row['EventTime'] = $eventTime;

    $userName = $row["UserName"];
    $row['UserName'] = $userName;

    $deviceName = $row["DeviceName"];
    $row['DeviceName'] = $deviceName;

    $doorName = $row["DoorName"];
    $row['DoorName'] = $doorName;

    $openDoorType = $row["OpenDoorType"];
    $row['OpenDoorType'] = $openDoorType;

    $inOrOutState = $row["InOrOutState"];
    $row['InOrOutState'] = $inOrOutState;

    $bHaveRecord = $row["bHaveRecord"];
    $row['bHaveRecord'] = $bHaveRecord;

    $cameraID = $row["CamerID"];
    $row['CamerID'] = $cameraID;



    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();


// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>