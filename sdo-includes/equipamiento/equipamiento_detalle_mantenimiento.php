<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

$tipo = $_POST['tipo'] ?? '';
$idCarpeta = (int)($_POST['idCarpeta'] ?? 0);


$sql = "SELECT *
        FROM ordenes_trabajos_general
        WHERE idCategoria = ?
        AND tipoMantencion = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("is", $idCarpeta, $tipo);
$stmt->execute();

$res = $stmt->get_result();
?>

<table class="table table-sm table-bordered table-hover">
    <thead class="thead-dark text-center">
    <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Equipo</th>
        <th>Observación</th>
    </tr>
    </thead>

    <tbody>

    <?php while($row = $res->fetch_assoc()){ ?>

        <tr>

            <td><?= $row['idOrden'] ?></td>
            <td><?= $row['fechaRegistro'] ?></td>
            <td><?= $row['nombreEquipo'] ?></td>
            <td><?= $row['observacion'] ?></td>

        </tr>

    <?php } ?>

    </tbody>
</table>