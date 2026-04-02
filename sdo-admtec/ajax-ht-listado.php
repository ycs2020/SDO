<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$idDepartamento = $_POST['idDepartamento'];

// Consulta SQL
$sql = "SELECT ot.*, eq.nombreEquipo, eq.numeroSerie
        FROM ordenes_trabajos_general ot
        LEFT JOIN equipamiento_hbqp eq 
            ON ot.idEquipo = eq.id
        WHERE ot.idDepartamento = ?
        ORDER BY ot.fechaOrden DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $folioOrden = $row["folioOrden"];
    $nomTecnicoRecepcionador = $row["nomTecnicoRecepcionador"];
    $nomSolicitante = $row["nomSolicitante"];

    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));

    $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

    $folioOrden = mb_convert_case($folioOrden, MB_CASE_UPPER, 'UTF-8');
    $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8');
    $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, 'UTF-8');

    $nombreEquipo = $row["nombreEquipo"] ?? '-';
    $numeroSerie = $row["numeroSerie"] ?? '-';

    $nombreEquipo = mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8');
    $numeroSerie = mb_convert_case($numeroSerie, MB_CASE_UPPER, 'UTF-8');

    $idOrden = $row["idOrden"];

    $btnVisualizar = "Deshabilitado";

    if($idOrden > 0 && $idDepartamento > 0){
        if($idDepartamento == 6){
            $btnVisualizar = "<form style='margin: 0; text-align: center;' target='_blank' action='../sdo-pdf/SDO-OrdenTrabajoGeneral-Inge-Clinica.php' method='POST'>";
            $btnVisualizar .= "<input type='hidden' name='idOrden' value='$idOrden'>";
            $btnVisualizar .= "<button type='submit' class='btn btn-block btn-primary'><i class='fas fa-eye'></i></button>";
            $btnVisualizar .= "</form>";
        }else{
            $btnVisualizar = "<form style='margin: 0; text-align: center;'  action='ordenes-menu-admin.php' method='POST'>";
            $btnVisualizar .= "<input type='hidden' name='idOrden' value='$idOrden'>";
            $btnVisualizar .= "<button type='submit' class='btn btn-block btn-primary'><i class='fas fa-eye'></i></button>";
            $btnVisualizar .= "</form>";
        }
    }

    $row['folioOrden'] = $folioOrden;
    $row['nomTecnicoRecepcionador'] = $nomTecnicoRecepcionador;
    $row['nomSolicitante'] = $nomSolicitante;
    $row['fechaOrden'] = $fechaOrden;
    $row['btnVisualizar'] = $btnVisualizar;
    $row['nombreEquipo'] = $nombreEquipo;
    $row['numeroSerie'] = $numeroSerie;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);


?>