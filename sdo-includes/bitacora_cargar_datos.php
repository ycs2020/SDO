<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');


// Consulta SQL
$sql = "SELECT 
          b.idBitacora,
          b.observacionBitacora,
          b.fechaBitacora,
          b.tecnicoBitacora,
          s.nomSubcategoria
        FROM 
          bitacora b
        JOIN 
          subcategorias s ON b.idSubcategoria = s.idSubcategoria
        ORDER BY 
          b.fechaBitacora DESC;
        ";

$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();


// Procesar los resultados
while ($row = $result->fetch_assoc()) {

    $idBitacora = $row["idBitacora"];
    $observacionBitacora = $row["observacionBitacora"];
    $fechaBitacora = $row["fechaBitacora"];
    $tecnicoBitacora = $row["tecnicoBitacora"];
    $nomSubcategoria = $row["nomSubcategoria"];

    $fechaTime = strtotime($fechaBitacora);

    if($fechaTime !== false){
        $fechaPalabras = fechaEspañol2($fechaTime);
        $horaPalabras = date('H:i',$fechaTime).' hrs.';
        $fechaCompleta = $fechaPalabras.'<br>'.$horaPalabras;
    }else{
        $fechaCompleta = 'No se ha podido obtener la fecha';
    }

    if(empty($tecnicoBitacora)){
        $tecnicoBitacora = 'No asignado';
    }

    if(empty($nomSubcategoria)){
        $nomSubcategoria = 'No asignado';
    }

    $row['idBitacora'] = $idBitacora;
    $row['observacionBitacora'] = $observacionBitacora;
    $row['fechaBitacora'] = $fechaBitacora;
    $row['fechaCompleta'] = $fechaCompleta;
    $row['tecnicoBitacora'] = $tecnicoBitacora;
    $row['nomSubcategoria'] = $nomSubcategoria;



    $datos[] = $row;

}

// Cerrar el statement y la conexión
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
header('Content-Type: application/json');
echo json_encode(array('data' => $datos));
?>
