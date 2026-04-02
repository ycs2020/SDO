<?php
require_once 'conexion.php'; // o el archivo que conecta con tu BD

$idAviso = $_POST['idAviso'] ?? null;
$nomUsuario = $_POST['nomUsuario'] ?? null;

if ($idAviso && $nomUsuario) {
    $stmt = $conexion->prepare("UPDATE avisos_notificaciones SET leida = 1 WHERE idAviso = ? AND usuario LIKE ?");
    $stmt->bind_param("is", $idAviso, $nomUsuario);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Faltan parámetros']);
}
?>
