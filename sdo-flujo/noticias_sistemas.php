<?php

include("../sdo-funciones/conexion.php"); // Asegúrate de tener la conexión a la BD
include("../sdo-funciones/funciones.php"); // Aquí debe estar la función fechaEspañol2()

// 1. Sistemas específicos con nombres visibles
$sistemas = [
    "Electricidad" => "Electricidad",
    "Equipos de Apoyo Clinico" => "Equipos de Apoyo Clínico",
    "Equipos Industriales" => "Equipos Industriales",
    "Infraestructura" => "Infraestructura",
    "Inst. Térmicas, Climatización y Refrigeración" => "Climatización"
];

// Ordenar alfabéticamente por nombre visible
asort($sistemas);

// Mostrar tarjetas por sistema específico
foreach ($sistemas as $valorSQL => $nombreMostrar) {
    echo '<div class="col-lg-2 col-md-4 col-12 d-flex">';
    echo '  <div class="card h-100 w-100">';
    echo '    <div class="card-header text-center bg-blue">';
    echo '      <h5 class="text-center w-100 text-bold">' . htmlspecialchars($nombreMostrar) . '</h5>';
    echo '    </div>';
    echo '    <div class="card-body d-flex flex-column justify-content-start align-items-start">';

    $sqlSistemas = "SELECT tituloAviso, fechaAviso FROM avisos_relevantes 
                    WHERE sistemaAviso LIKE '$valorSQL' AND estadoAviso = 'Activo' 
                    ORDER BY fechaAviso DESC";
    $resSistemas = mysqli_query($conexion, $sqlSistemas);

    if (mysqli_num_rows($resSistemas) > 0) {
        while ($row = mysqli_fetch_assoc($resSistemas)) {
            $fechaTime = strtotime($row["fechaAviso"]);
            $fechaHora = fechaEspañol2($fechaTime);
            $horaAviso = date('H:i', $fechaTime);
            echo '<div class="alert alert-warning p-2 mb-2 text-center w-100">';
            echo '  <strong>' . $fechaHora . '<br>' . $horaAviso . '<br><br>' . htmlspecialchars($row["tituloAviso"]) . '</strong>';
            echo '</div>';
        }
    } else {
        echo "<div class='text-muted w-100 text-center'>Sin avisos</div>";
    }

    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}

// 2. Sistemas excluidos
$sistemasExcluidos = array_keys($sistemas);
$sistemasExcluidos[] = "Control Centralizado";

$sistemasExcluidosSQL = "'" . implode("','", array_map(function ($s) use ($conexion) {
        return mysqli_real_escape_string($conexion, $s);
    }, $sistemasExcluidos)) . "'";

// 3. Avisos de Ingeniería Operacional (otros sistemas)
$sqlOtros = "
    SELECT sistemaAviso, tituloAviso, fechaAviso 
    FROM avisos_relevantes 
    WHERE sistemaAviso NOT IN ($sistemasExcluidosSQL)
    AND estadoAviso = 'Activo' 
    ORDER BY fechaAviso DESC
";
$resOtros = mysqli_query($conexion, $sqlOtros);

echo '<div class="col-lg-2 col-md-4 col-12 d-flex">';
echo '  <div class="card h-100 w-100">';
echo '    <div class="card-header text-center bg-blue">';
echo '      <h5 class="text-center w-100 text-bold">Ingeniería Operacional</h5>';
echo '    </div>';
echo '    <div class="card-body d-flex flex-column justify-content-start align-items-start">';

if (mysqli_num_rows($resOtros) > 0) {
    while ($row = mysqli_fetch_assoc($resOtros)) {
        $fechaTime = strtotime($row["fechaAviso"]);
        $fechaHora = fechaEspañol2($fechaTime);
        $horaAviso = date('H:i', $fechaTime);
        echo '<div class="alert alert-warning p-2 mb-2 text-center w-100">';
        echo '  <strong>' . $fechaHora . '<br>' . $horaAviso . '<br><br>' . htmlspecialchars($row["tituloAviso"]) . '</strong>';
        echo '</div>';
    }
} else {
    echo "<div class='text-muted w-100 text-center'>Sin avisos</div>";
}

echo '    </div>';
echo '  </div>';
echo '</div>';

