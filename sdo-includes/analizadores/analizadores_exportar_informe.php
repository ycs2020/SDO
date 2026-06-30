<?php
// analizadores_exportar_informe.php
include '../../sdo-funciones/conexion.php';

// Definir el nombre del archivo con la fecha actual del sistema
date_default_timezone_set('America/Santiago');
$fechaActual = date('d-m-Y_H-i');
$filename = "Informe_Analizadores_Red_" . $fechaActual . ".xls";

// Configurar cabeceras HTTP para forzar la descarga en formato Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Consulta para traer los analizadores agrupados por piso
$query = "SELECT idAnalizador, nomAnalizador, pisoAnalizador, sectorAnalizador 
          FROM analizadores_red 
          ORDER BY pisoAnalizador ASC, nomAnalizador ASC";

?>
<style>
    .txt-center { text-align: center; }
    .header-table { background-color: #1e3a8a; color: #ffffff; font-weight: bold; text-align: center; }
    .piso-title { background-color: #f3f4f6; font-weight: bold; }
</style>

<table border="1">
    <thead>
    <tr>
        <th colspan="4" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #3b82f6; color: white;">
            INFORME GENERAL - ANALIZADORES DE RED REGISTRADOS
        </th>
    </tr>
    <tr>
        <th class="header-table" style="width: 80px;">N° ID</th>
        <th class="header-table" style="width: 300px;">Nombre del Analizador</th>
        <th class="header-table" style="width: 100px;">Piso</th>
        <th class="header-table" style="width: 250px;">Sector / Sala</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($resultado = $conexion->query($query)) {
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                ?>
                <tr>
                    <td class="txt-center"><?= $fila['idAnalizador'] ?></td>
                    <td><?= htmlspecialchars($fila['nomAnalizador']) ?></td>
                    <td class="txt-center">Piso <?= $fila['pisoAnalizador'] ?></td>
                    <td><?= htmlspecialchars($fila['sectorAnalizador']) ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='4' class='txt-center'>No existen analizadores registrados en el sistema.</td></tr>";
        }
        $resultado->free();
    } else {
        echo "<tr><td colspan='4' class='txt-center'>Error al consultar la base de datos.</td></tr>";
    }
    ?>
    </tbody>
</table>