<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM reportes_generales ORDER BY fechaReporte DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$datos = array();

$nomUsuarioBuscar = '';

if(isset($_POST['nomUsuarioModificar'])){
    $nomUsuarioModificar = $_POST['nomUsuarioModificar'];
    if(empty($nomUsuarioModificar)){
        $nomUsuarioBuscar = 'No Aplica';
    }else{
        $nomUsuarioBuscar = $nomUsuarioModificar;
    }
}else{
    $nomUsuarioBuscar = 'No Aplica';
}

while ($row = $result->fetch_assoc()) {
    $folioReporte = $row["folioReporte"];
    $nomEncargadoTurno = $row["nomEncargadoTurno"];
    $fechaReporte = $row["fechaReporte"];
    $idReporte = $row["idReporte"];

    if(empty($fechaReporte)){
        $fechaReporteFinal = 'No Aplica.';
    }else{

        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaReporte)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaReporte)));

        $fechaReporteFinal = $inicioFecha . '<br>' . $inicioHora . ' hrs.';
    }

    $nombres = explode(" - ", $nomEncargadoTurno);

    $encontrado = false;
    foreach ($nombres as $nombre) {
        if (strpos($nombre, $nomUsuarioBuscar) !== false) {
            $encontrado = true;
            break;
        }
    }

    if ($encontrado) {
        $aplica = 'si';
    }else{
        $aplica = 'no';
    }


    $folioReporte = mb_convert_case($folioReporte, MB_CASE_UPPER, 'UTF-8');
    $nomEncargadoTurno = mb_convert_case($nomEncargadoTurno, MB_CASE_UPPER, 'UTF-8');

    $row['folioReporte'] = $folioReporte;
    $row['nomEncargadoTurno'] = $nomEncargadoTurno;
    $row['fechaReporteFinal'] = $fechaReporteFinal;
    $row['idReporte'] = $idReporte;
    $row['aplicaModificar'] = $aplica;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
    //$datos1[] = $rowArchivo;
}

$stmt->close();
$conexion->close();

echo json_encode($datos);

?>