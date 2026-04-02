<?php
include_once '../sdo-funciones/conexion.php';

$idFormFuncCCAA = (int)$_POST['idFormFuncCCAA'];
$unidadFormFuncCCAA = mysqli_real_escape_string($conexion, $_POST['unidadFormFuncCCAA']);
$nomFormFuncCCAA = mysqli_real_escape_string($conexion, $_POST['nomFormFuncCCAA']);
$nomFormFuncCCAATitle = mb_convert_case(trim($nomFormFuncCCAA), MB_CASE_TITLE, 'UTF-8');
$rutFormFuncCCAA = mysqli_real_escape_string($conexion, $_POST['rutFormFuncCCAA']);
$rutFormFuncCCAATitle = mb_convert_case(trim($rutFormFuncCCAA), MB_CASE_TITLE, 'UTF-8');

// Construcción de lista de columnas
$cols = [];
for ($i = 1; $i <= 18; $i++) {
    $cols[] = "door{$i}FormCCAA";
}


$texto = implode(",", $cols);

// 1. Obtener permisos del funcionario (0/1)
$sqlFunc = "SELECT $texto
            FROM formularios_funcionarios
            WHERE idFormFuncCCAA = $idFormFuncCCAA
            LIMIT 1";
$resFunc = mysqli_query($conexion, $sqlFunc);
$permisos = ($resFunc && mysqli_num_rows($resFunc) > 0) ? mysqli_fetch_assoc($resFunc) : [];

// 2. Obtener nombres de las puertas de la unidad
$sqlAcceso = "SELECT $texto
              FROM formularios_acceso
              WHERE idFormCCAA = '$unidadFormFuncCCAA'
              LIMIT 1";
$resAcceso = mysqli_query($conexion, $sqlAcceso);
$nombresPuertas = ($resAcceso && mysqli_num_rows($resAcceso) > 0) ? mysqli_fetch_assoc($resAcceso) : [];

if ($permisos && $nombresPuertas) {
    ?>
    <p>
        <b>Nombre: </b><?php echo $nomFormFuncCCAATitle; ?>
    </p>
    <p>
        <b>RUT: </b><?php echo $rutFormFuncCCAATitle; ?>
    </p>
    <?php

    echo "<table id='tablaAutorizaciones' class='table table-bordered table-hover table-sm'>
            <thead class='bg-primary text-white'>
              <tr>
                <th style='width:70%; text-align: center;'>Puerta / Área</th>
                <th style='width:30%; text-align: center;'>Autorización</th>
              </tr>
            </thead>
            <tbody>";

    for ($i = 1; $i <= 18; $i++) {
        $nombre = $nombresPuertas["door{$i}FormCCAA"];
        $valor = (int)$permisos["door{$i}FormCCAA"];

        // Mostrar solo si existe nombre configurado
        if (!empty($nombre)) {
            $badge = $valor === 1
                ? "<span class='badge badge-success'>Permitido</span>"
                : "<span class='badge badge-danger'>Denegado</span>";

            echo "<tr>
                    <td>$nombre</td>
                    <td class='text-center'>$badge</td>
                  </tr>";
        }
    }

    echo "</tbody></table>";

} else {
    echo "<p class='text-muted'>No se encontraron autorizaciones configuradas para esta unidad.</p>";
}
?>
