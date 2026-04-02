<?php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM protoc_ccaa_grupo ORDER BY nomGrupo ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $idGrupo = $row["idGrupo"];
    $nomGrupo = $row["nomGrupo"];
    $fechaGrupo = $row["fechaGrupo"];
    $fechaValida = (strtotime($fechaGrupo)) ? date('d/m/Y', strtotime($fechaGrupo)) : 'No Aplica';
    $descripGrupo = $row["descripGrupo"];

    if(empty($descripGrupo)){
        $descripGrupo = 'Sin Información';
    }

//    $fechaValida = (strtotime($fechaGrupo)) ? fechaEspañol2($fechaGrupo) : 'No Aplica';

    if (!empty($row["idGrupo"]) && $row["idGrupo"] > 0) {
        $row["botonGrupo"] = '
        <form style="margin: 0; text-align: center;" action="ccaa_grupos_protocolo_detalle.php" target="_blank" method="post">
            <input type="hidden" name="idGrupoCargar" value="' . $row["idGrupo"] . '">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar
            </button>
        </form>';
    } else {
        $row["botonGrupo"] = '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>';
    }


    $row["idGrupo"] = $idGrupo;
    $row["nomGrupo"] = $nomGrupo;
    $row["fechaGrupo"] = $fechaGrupo;
    $row["descripGrupo"] = $descripGrupo;
    $row["fechaValida"] = $fechaValida;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>