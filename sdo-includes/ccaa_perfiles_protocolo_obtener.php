<?php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM protoc_ccaa_perfil ORDER BY nomPerfil ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $idPerfil = $row["idPerfil"];
    $nomPerfil = $row["nomPerfil"];
    $fechaPerfil = $row["fechaPerfil"];
    $fechaValida = (strtotime($fechaPerfil)) ? date('d/m/Y', strtotime($fechaPerfil)) : 'No Aplica';
    $descripPerfil = $row["descripPerfil"];

    if(empty($descripPerfil)){
        $descripPerfil = 'Sin Información';
    }

//    $fechaValida = (strtotime($fechaPerfil)) ? fechaEspañol2($fechaPerfil) : 'No Aplica';

    if (!empty($row["idPerfil"]) && $row["idPerfil"] > 0) {
        $row["botonPerfil"] = '
        <form style="margin: 0; text-align: center;" action="ccaa_perfiles_protocolo_detalle.php" target="_blank" method="post">
            <input type="hidden" name="idPerfilCargar" value="' . $row["idPerfil"] . '">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar
            </button>
        </form>';
    } else {
        $row["botonPerfil"] = '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>';
    }


    $row["idPerfil"] = $idPerfil;
    $row["nomPerfil"] = $nomPerfil;
    $row["fechaPerfil"] = $fechaPerfil;
    $row["descripPerfil"] = $descripPerfil;
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