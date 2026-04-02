<?php
include '../../sdo-funciones/conexion.php';

header('Content-Type: application/json');

$idAviso = intval($_POST['idAviso'] ?? 0);

// Obtener los ya asignados
$asignados = [];
$sqlAsignados = "SELECT nomDestinatario FROM avisos_destinatarios WHERE idAviso = $idAviso";
$resAsignados = mysqli_query($conexion, $sqlAsignados);
while ($row = mysqli_fetch_assoc($resAsignados)) {
    $asignados[] = $row['nomDestinatario'];
}

// Obtener todos los candidatos
$usuariosAvisos = [];

$sql = "SELECT jt.nomJefe AS nomEmpleado FROM jefes_tecnicos jt
        WHERE jt.nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%'
          AND jt.nomJefe NOT LIKE '%Prueba%'
          AND jt.userActivado = 1

        UNION

        SELECT d.nomDirectivo AS nomEmpleado FROM directivos d

        ORDER BY nomEmpleado ASC";
$res = mysqli_query($conexion, $sql);

while ($row = mysqli_fetch_assoc($res)) {
    $nombre = $row['nomEmpleado'];

    if ($nombre == 'Alejandro Cantillano') {
        $nombre = 'Jorge Cantillano';
    }

    if (!in_array($nombre, $asignados)) {
        $usuariosAvisos[] = $nombre;
    }
}

echo json_encode($usuariosAvisos);
