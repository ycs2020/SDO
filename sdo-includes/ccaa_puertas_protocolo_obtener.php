<?php
include_once '../sdo-funciones/conexion.php';

$idGrupoVer = isset($_POST['idGrupoVer']) ? (int)$_POST['idGrupoVer'] : 0;

$sqlPuertas = "
    SELECT 
        pcgp.idGrupoPuerta AS idGrupoPuerta,
        UPPER(ad.name) AS puerta,
        UPPER(ul.name) AS uca
    FROM protoc_ccaa_grupo_puerta pcgp
    INNER JOIN acs_door ad ON (pcgp.numPuerta - 1) = ad.no AND pcgp.numUCA = ad.idDevice
    INNER JOIN ucas_listado ul ON pcgp.numUCA = ul.idUnique
    WHERE pcgp.idGrupo = $idGrupoVer
    ORDER BY
        CASE
            WHEN ul.name LIKE '%-P0' THEN 0
            WHEN ul.name LIKE '%-P1' THEN 1
            WHEN ul.name LIKE '%-P2' THEN 2
            WHEN ul.name LIKE '%-P3' THEN 3
            WHEN ul.name LIKE '%-P4' THEN 4
            WHEN ul.name LIKE '%-P5' THEN 5
            WHEN ul.name LIKE '%-P6' THEN 6
            ELSE 99
        END,
        ul.name ASC,
        ad.name ASC
";

$resPuertas = mysqli_query($conexion, $sqlPuertas);

if ($resPuertas && mysqli_num_rows($resPuertas) > 0) {
    echo "<table id='tablaPuertasGrupo' class='table table-bordered table-hover table-sm'>
            <thead class='bg-primary text-white text-center'>
              <tr>
                <th style='width:10%;'>N°</th>
                <th style='width:45%;'>Puerta</th>
                <th style='width:45%;'>UCA</th>
              </tr>
            </thead>
            <tbody>";

    $numPuertas = 1;
    while ($row = mysqli_fetch_assoc($resPuertas)) {
        echo "<tr>
                <td class='text-center align-middle'>{$numPuertas}</td>
                <td>{$row['puerta']}</td>
                <td>{$row['uca']}</td>
              </tr>";
        $numPuertas++;
    }

    echo "</tbody></table>";
} else {
    echo "<p class='text-muted'>No se encontraron autorizaciones configuradas para esta unidad.</p>";
}
?>
