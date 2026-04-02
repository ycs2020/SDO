<?php
include_once('../sdo-funciones/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idBloqueo = $_POST['idBloqueo'];
    $observacion = trim($_POST['observacion']);
    $idActa = $_POST['idActaBloqueo'];

    // Guarda también el usuario que desbloquea si usas sesiones
    session_start();
    $usuario = $_SESSION['usuario'] ?? 'desconocido';

    $sqlBloqueoActa = "UPDATE actas SET bloqueoActa = 0 WHERE idActa = $idActa";
    $resBloqueoActa = mysqli_query($conexion, $sqlBloqueoActa);
    if($resBloqueoActa){
        $sql = "";
    }

    if (mysqli_query($conexion, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
    }
}
?>