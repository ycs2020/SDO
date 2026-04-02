<?php
// bms_modal_cant_alarmas.php
header('Content-Type: text/html; charset=UTF-8');
include '../sdo-funciones/conexion.php';

$idRegistro = isset($_POST['idRegistro']) ? (int)$_POST['idRegistro'] : 0;

if ($idRegistro < 1) {
    echo '<div class="alert alert-warning mb-0">ID de registro no válido.</div>';
    exit;
}

// ...consulta...
$stmt = $conexion->prepare("SELECT COUNT(*) AS cant FROM bms_alarmas_historial WHERE idRegistro = ?");
$stmt->bind_param("i", $idRegistro);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$cant = (int)$res['cant'];

// Devuelves HTML (tabla, cards, etc.)
?>
<div>
<!--    <p class="mb-2"><strong>ID Registro:</strong> --><?php //= htmlspecialchars($idRegistro) ?><!--</p>-->
    <h4 class="mb-3">Total de alarmas: <?= $cant ?></h4>
    <!-- más contenido si necesitas -->
</div>
