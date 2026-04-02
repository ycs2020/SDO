<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

$buscador = '';

if (isset($_POST['idCarpetaAjax'])) {
    $idCarpetaAjax = $_POST['idCarpetaAjax'];

    if ($idCarpetaAjax > 0) {
        $idCarpetaAjax = mysqli_real_escape_string($conexion, $idCarpetaAjax);

        $getIDCategoriaSistemaCCTV = getIDCategoriaSistemaCCTV();
        $getIDCategoriaSistemaCCAA = getIDCategoriaSistemaCCAA();
        $getIDCategoriaSistemaBMS = getIDCategoriaSistemaBMS();

        if ($idCarpetaAjax == $getIDCategoriaSistemaCCTV) {
            $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpetaAjax ORDER BY piso ASC, nombreEquipo ASC";
        } else if ($idCarpetaAjax == $getIDCategoriaSistemaCCAA) {
            $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpetaAjax AND (modelo = 'ASC1208C-S' OR  modelo = 'ASC1204C-S') ORDER BY piso ASC, nombreEquipo ASC";
        } else if ($idCarpetaAjax == $getIDCategoriaSistemaBMS) {
//            $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE (idCategoria = $idCarpetaAjax) AND (nombreEquipo LIKE '%BOS%' OR modelo LIKE 'BMS')  ORDER BY nombreEquipo ASC";
            $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE (idCategoria = $idCarpetaAjax) AND (modelo LIKE 'BMS') AND nombreEquipo NOT LIKE ('%UMA-%') AND nombreEquipo NOT LIKE ('%Equipos HBQP%')ORDER BY nombreEquipo ASC";
//            $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE (idCategoria = $idCarpetaAjax) AND nombreEquipo LIKE ('%Unidad Manejadora de Aire%') ORDER BY nombreEquipo ASC";
        } else {
            $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpetaAjax ORDER BY  ordenEquipo ASC,piso ASC, pma ASC, nombreEquipo ASC";
        }

// Preparar la consulta
        $stmt = $conexion->prepare($sqlEquipo);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {

            $idEquipo = $row['id'];
            $identificadorUnico = $row['identificadorUnico'];
            $nombreEquipo = $row['nombreEquipo'];
            $recinto = $row['recinto'];
            $pma = $row['pma'];
            $piso = $row['piso'];
            $estadoEquipo = $row['estadoEquipo'];
            $idCategoria = $row['idCategoria'];
            $tipoMenu = $row['tipoMenu'];

            $idEquipo = max($idEquipo, 0);
            $nombreEquipo = !empty($nombreEquipo) ? $nombreEquipo : '-';
            $recinto = !empty($recinto) ? $recinto : '-';
            $pma = !empty($pma) ? $pma : '-';
            $piso = !empty($piso) ? $piso : '-';
            $estadoEquipo = !empty($estadoEquipo) ? $estadoEquipo : '-';
            $idCategoria = max($idCategoria,0);
            $tipoMenu = !empty($tipoMenu) ? $tipoMenu : '-';


            $row['id'] = $idEquipo;
            $row['identificadorUnico'] = $identificadorUnico;
            $row['nombreEquipo'] = $nombreEquipo;
            $row['recinto'] = $recinto;
            $row['pma'] = $pma;
            $row['piso'] = $piso;
            $row['estadoEquipo'] = $estadoEquipo;
            $row['idCategoria'] = $idCategoria;
            $row['tipoMenu'] = $tipoMenu;

            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);

    } else {
        echo '';
    }

} else {
    echo '';
}
?>