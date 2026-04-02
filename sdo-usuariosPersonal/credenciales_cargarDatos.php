<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
// Obtener la categoría seleccionada desde la petición POST

// Consulta SQL
$sql = "SELECT * FROM credenciales_personal ORDER BY fechaCreacionCredencial DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//        $stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $rutCredencial = $row["rutCredencial"];
    $nomCredencial = $row["nomCredencial"];
    $cargoCredencial = $row["cargoCredencial"];
    $tipoCredencial = $row["tipoCredencial"];
    $unidadCredencial = $row['unidadCredencial'];
    $codigoCredencial = $row['codigoCredencial'];
    $fechaCreacionCredencial = $row['fechaCreacionCredencial'];
    $fechaEntregaCredencial = $row['fechaEntregaCredencial'];
    $fechaBajaCredencial = $row['fechaBajaCredencial'];
    $nomCreadorCredencial = $row['nomCreadorCredencial'];
    $nomEntregaCredencial = $row['nomEntregaCredencial'];

    if(empty($fechaCreacionCredencial) || $fechaCreacionCredencial == NULL){
        $creacion = 'No Aplica';
    }else{
        $fechaCreacion = date('d/m/Y', strtotime(str_replace('/', '-', $fechaCreacionCredencial)));
        $horaCreacion = date('H:i', strtotime(str_replace('/', '-', $fechaCreacionCredencial)));

        $creacion = $fechaCreacion . ' a las ' . $horaCreacion . ' hrs.';
    }

    if(empty($fechaEntregaCredencial) || $fechaEntregaCredencial == NULL){
        $entrega = 'No Aplica';
    }else{
        $fechaEntrega = date('d/m/Y', strtotime(str_replace('/', '-', $fechaEntregaCredencial)));
        $horaEntrega = date('H:i', strtotime(str_replace('/', '-', $fechaEntregaCredencial)));

        $entrega = $fechaEntrega . ' a las ' . $horaEntrega . ' hrs.';
    }

    if(empty($fechaBajaCredencial) || $fechaBajaCredencial == NULL){
        $baja = 'No Aplica';
    }else{
        $fechaBaja = date('d/m/Y', strtotime(str_replace('/', '-', $fechaBajaCredencial)));
        $horaBaja = date('H:i', strtotime(str_replace('/', '-', $fechaBajaCredencial)));

        $baja = $fechaBaja . ' a las ' . $horaBaja . ' hrs.';
    }

    if(empty($rutCredencial)){
        $rutCredencial2 = 'No Aplica';
    }else{
        $rutCredencial2 = $rutCredencial;
    }

    if(empty($nomCredencial)){
        $nomCredencial2 = 'No Aplica';
    }else{
        $nomCredencial2 = $nomCredencial;
    }

    if(empty($cargoCredencial)){
        $cargoCredencial2 = 'No Aplica';
    }else{
        $cargoCredencial2 = $cargoCredencial;
    }

    if(empty($tipoCredencial)){
        $tipoCredencial2 = 'No Aplica';
    }else{
        $tipoCredencial2 = $tipoCredencial;
    }

    if(empty($unidadCredencial)){
        $unidadCredencial2 = 'No Aplica';
    }else{
        $unidadCredencial2 = $unidadCredencial;
    }

    if(empty($codigoCredencial)){
        $codigoCredencial2 = 'No Aplica';
    }else{
        $codigoCredencial2 = $codigoCredencial;
    }

    if(empty($nomCreadorCredencial)){
        $nomCreadorCredencial2 = 'No Aplica';
    }else{
        $nomCreadorCredencial2 = $nomCreadorCredencial;
    }

    if(empty($nomEntregaCredencial)){
        $nomEntregaCredencial2 = 'No Aplica';
    }else{
        $nomEntregaCredencial2 = $nomEntregaCredencial;
    }


    $rutCredencial2 = mb_convert_case($rutCredencial2, MB_CASE_TITLE, 'UTF-8');
    $nomCredencial2 = mb_convert_case($nomCredencial2, MB_CASE_TITLE, 'UTF-8');
    $cargoCredencial2 = mb_convert_case($cargoCredencial2, MB_CASE_TITLE, 'UTF-8');
    $tipoCredencial2 = mb_convert_case($tipoCredencial2, MB_CASE_TITLE, 'UTF-8');
    $unidadCredencial2 = mb_convert_case($unidadCredencial2, MB_CASE_TITLE, 'UTF-8');
    $codigoCredencial2 = mb_convert_case($codigoCredencial2, MB_CASE_UPPER, 'UTF-8');
    $nomCreadorCredencial2 = mb_convert_case($nomCreadorCredencial2, MB_CASE_TITLE, 'UTF-8');
    $nomEntregaCredencial2 = mb_convert_case($nomEntregaCredencial2,MB_CASE_TITLE,'UTF-8');

    $row['rutCredencial'] = $rutCredencial2;
    $row['nomCredencial'] = $nomCredencial2;
    $row['cargoCredencial'] = $cargoCredencial2;
    $row['tipoCredencial'] = $tipoCredencial;
    $row['unidadCredencial'] = $unidadCredencial2;
    $row['codigoCredencial'] = $codigoCredencial2;
    $row['fechaCreacionCredencial'] = $creacion;
    $row['fechaEntregaCredencial'] = $entrega;
    $row['fechaBajaCredencial'] = $baja;
    $row['nomCreadorCredencial'] = $nomCreadorCredencial2;
    $row['fechaCreacionEditar'] = $fechaCreacionCredencial;
    $row['fechaEntregaEditar'] = $fechaEntregaCredencial;
    $row['fechaBajaEditar'] = $fechaBajaCredencial;
    $row['nomEntregaCredencial'] = $nomEntregaCredencial2;



    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}



// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>