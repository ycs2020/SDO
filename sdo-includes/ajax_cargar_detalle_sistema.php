<?php
ini_set('display_errors', 0); // Desactivar en producción por seguridad
error_reporting(E_ALL);

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$idCategoria = isset($_POST['idCategoria']) ? (int)$_POST['idCategoria'] : 0;

if ($idCategoria <= 0) {
    echo "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle'></i> ID de categoría inválido o no enviado.</div>";
    exit;
}

// Prepared Statement para prevenir SQLi
$sql = "SELECT DISTINCT modelo, marca 
        FROM equipamiento_hbqp 
        WHERE idCategoria = ? 
          AND modelo IS NOT NULL 
          AND modelo <> ''";

$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $idCategoria);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-warning text-center'><i class='fas fa-info-circle'></i> No se encontraron datos para esta categoría.</div>";
        mysqli_stmt_close($stmt);
        exit;
    }

    $totalGeneral = 0;
    ?>
    <div class="table-responsive">
        <table id="tablaValorEquipos" class="table table-striped table-bordered table-hover w-100">
            <thead class="bg-purple text-white text-center">
            <tr>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Cantidad</th>
                <th>Precio Unitario (Con IVA)</th>
                <th>Precio Total (Con IVA)</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $modelo = mb_convert_case($row['modelo'] ?: 'No Aplica', MB_CASE_UPPER, 'UTF-8');
                $marca  = mb_convert_case($row['marca'] ?: 'No Aplica', MB_CASE_UPPER, 'UTF-8');

                $precioUnitario = obtenerMayorValorEquipamiento($modelo, $idCategoria);
                $precioUnitario = is_numeric($precioUnitario) ? floatval($precioUnitario) : 0;
                $precioUnitarioTexto = $precioUnitario > 0 ? moneda_chilena($precioUnitario) : '$ 0';

                $cantidad = contarModelosDistintos($modelo, $idCategoria);
                $cantidad = is_numeric($cantidad) ? intval($cantidad) : 0;

                $precioTotal = $precioUnitario * $cantidad;
                $precioTotalTexto = $precioTotal > 0 ? moneda_chilena($precioTotal) : '$ 0';

                $totalGeneral += $precioTotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($modelo); ?></td>
                    <td><?php echo htmlspecialchars($marca); ?></td>
                    <td class="text-center font-weight-bold"><?php echo $cantidad; ?></td>
                    <td class="text-right"><?php echo $precioUnitarioTexto; ?></td>
                    <td class="text-right font-weight-bold text-purple"><?php echo $precioTotalTexto; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            <tfoot class="bg-light text-center">
            <tr>
                <th colspan="4" class="text-right font-weight-bold">Gran Total General (Con IVA):</th>
                <th class="text-right font-weight-bold text-lg text-purple"><?php echo moneda_chilena($totalGeneral); ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php
    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'><i class='fas fa-bug'></i> Error interno al procesar la consulta.</div>";
}
?>