<?php
// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM reportes_generales ORDER BY fechaReporte DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $idReporte = $row["idReporte"];
    $folioReporte = $row["folioReporte"];
    $nomEncargadoTurno = $row["nomEncargadoTurno"];
    $fechaReporte = $row["fechaReporte"];
    $turnoReporte = $row["turnoReporte"];
    $turnoReporte = mb_convert_case($turnoReporte, MB_CASE_LOWER, 'UTF-8');
    $fechaTime = strtotime($fechaReporte);
    $fechaPalabras = fechaEspañol2($fechaTime);
    $horaPalabras = date('H:i',$fechaTime).' hrs.';

    switch ($turnoReporte) {
        case "dia":
            $cadenaFecha = "$fechaPalabras<br><b>Turno Día</b>";
            break;

        case "noche":
            $horaReporte = date("H:i", $fechaTime);
            if ($horaReporte > "19:59" && $horaReporte <= "23:59") {
                $fechaSiguiente = strtotime($fechaReporte . " +1 day");
//                $cadenaFecha = "Desde las 20:00 hrs. del $fechaPalabras hasta las 08:00 hrs. del " . fechaEspañol($fechaSiguiente);
                $cadenaFecha = "$fechaPalabras<br><b>Turno Noche</b>";
            } else {
                $fechaAnterior = strtotime($fechaReporte . " -1 day");
//                $cadenaFecha = "Desde las 20:00 hrs. del " . fechaEspañol($fechaAnterior) . " hasta las 08:00 hrs. del $fechaPalabras";
                $cadenaFecha = fechaEspañol2($fechaAnterior)."<br><b>Turno Noche</b>";
            }
            break;

        default:

            $cadenaFecha = $fechaPalabras.'<br>'.$horaPalabras;
    }

    $fechaCompleta = $fechaPalabras.'<br>'.$horaPalabras;

    $buttonDescarga = '';
    if($idReporte > 0){
        $buttonDescarga = "<button class='btn btn-success btn-sm btn-block btn-visualizar' data-id='$idReporte'><i class='fas fa-eye'></i></button>";
    }else{
        $buttonDescarga = "<span class='btn btn-danger btn-sm'>ID Reporte No Valido</span>";
    }

    $row['folioReporte'] = $folioReporte;
    $row['nomEncargadoTurno'] = $nomEncargadoTurno;
    $row['fechaReporte'] = $fechaReporte;
    $row['fechaReporte2'] = $fechaCompleta;
    $row['turnoReporte'] = $turnoReporte;
    $row['cadenaFecha'] = $cadenaFecha;
    $row['buttonDescarga'] = $buttonDescarga;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>