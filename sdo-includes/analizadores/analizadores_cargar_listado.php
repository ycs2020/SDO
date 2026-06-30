<?php
// get_datatable_piso.php
include '../../sdo-funciones/conexion.php'; // Asegúrate de incluir tu conexión real aquí

if (isset($_POST['piso'])) {
    $piso = (int)$_POST['piso'];

    // Consulta filtrada por el piso seleccionado
    $query = "SELECT idAnalizador, nomAnalizador, pisoAnalizador, sectorAnalizador 
              FROM analizadores_red 
              WHERE pisoAnalizador = ?";

    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("i", $piso);
        $stmt->execute();
        $resultado = $stmt->get_result();





        ?>
        <table id="tabla-data-piso-<?= $piso ?>" class="table table-bordered table-hover table-valign-middle w-100">
            <thead class="text-center bg-gradient-dark">
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Sector</th>
                <th>Visualizar</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($resultado->num_rows > 0) {
                $intVal = 1;

                while ($fila = $resultado->fetch_assoc()) {

                    $btnVisualizar = "Deshabilitado";

                    $idAnalizador = (int)$fila['idAnalizador'];

                    if($idAnalizador > 0){
                        $btnVisualizar = "<button class='btn btn-block btn-primary btVerAnalizador' id='btVerAnalizador' name='btVerAnalizador' data-id='$idAnalizador'><i class='fas fa-eye'></i></button>";
                    }

                    ?>
                    <tr>
                        <td><?= htmlspecialchars($intVal) ?></td>
                        <td><?= htmlspecialchars($fila['nomAnalizador']) ?></td>
                        <td><?= htmlspecialchars($fila['sectorAnalizador']) ?></td>
                        <td><?= $btnVisualizar ?></td>
                    </tr>
                    <?php
                    $intVal++;
                }
            }
            ?>
            </tbody>
        </table>
        <?php
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error en la consulta del servidor.</div>";
    }
} else {
    echo "<div class='alert alert-warning'>No se especificó un piso válido.</div>";
}
?>