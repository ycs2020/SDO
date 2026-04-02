<?php

// Archivo: tu_servidor.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

try {

// Consulta SQL
$sql = "SELECT * FROM inventario_equipos ORDER BY fechaInventario DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $idInventario = $row['idInventario'];
    $equipoInventario = $row['equipoInventario'];
    $marcaInventario = $row['marcaInventario'];
    $modeloInventario = $row['modeloInventario'];
    $cantidadInventario = $row['cantidadInventario'];
    $serieInventario = $row['serieInventario'];
    $unidad = $row['unidad'];
    $fechaInventario = $row['fechaInventario'];
    $categoriaInventario = $row['categoriaInventario'];
    $valorUnitario = $row['valorUnitario'];
    $nomProveedor = $row['nomProveedor'];
    $rutProveedor = $row['rutProveedor'];
    $tipoDocumento = $row['tipoDocumento'];
    $solicompra = $row['solicompra'];

    if(!empty($equipoInventario)){
        $equipoInventario2 = mb_convert_case($equipoInventario,MB_CASE_UPPER,'UTF-8');
    }else{
        $equipoInventario2 = 'NO APLICA';
    }

    if(!empty($marcaInventario)){
        $marcaInventario2 = mb_convert_case($marcaInventario,MB_CASE_UPPER,'UTF-8');
    }else{
        $marcaInventario2 = 'NO APLICA';
    }

    if(!empty($modeloInventario)){
        $modeloInventario2 = mb_convert_case($modeloInventario,MB_CASE_UPPER,'UTF-8');
    }else{
        $modeloInventario2 = 'NO APLICA';
    }

    if(!empty($serieInventario)){
        $serieInventario2 = mb_convert_case($serieInventario,MB_CASE_UPPER,'UTF-8');
    }else{
        $serieInventario2 = 'NO APLICA';
    }

    if(!empty($unidad)){
        $unidad2 = mb_convert_case($unidad,MB_CASE_UPPER,'UTF-8');
    }else{
        $unidad2 = 'NO APLICA';
    }

    if($cantidadInventario > 0){
        $cantidadInventario2 = $cantidadInventario;
    }else{
        $cantidadInventario2 = 0;
    }

    if(empty($fechaInventario)){
        $fechaInventario2 = 'NO APLICA';
    }else{

        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaInventario)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaInventario)));

        $fechaInventario2 = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
    }

    if($categoriaInventario > 0){
        $getNomCategoriaEquipamiento = getNomCategoriaEquipamiento($categoriaInventario);
        $getNomCategoriaEquipamiento2 = mb_convert_case($getNomCategoriaEquipamiento,MB_CASE_UPPER,'UTF-8');
    }else{
        $getNomCategoriaEquipamiento2 = 'NO APLICA';
    }

    if($valorUnitario > 0){
        $valorUnitario2 = number_format($valorUnitario, 0, ',', '.');
        $valorUnitario2 = '$'.$valorUnitario2;
    }else{
        $valorUnitario2 = '$0';
    }

    if($tipoDocumento == 'Cotizacion Internet'){

        $texto = str_replace("CI-","",$solicompra);

        if($texto > 0){
            $idParaDescarga = $texto;
        }else{
            $idParaDescarga = 'NO APLICA.';
        }

    }else if($tipoDocumento == 'Cotizacion Empresa'){

        $texto = str_replace("CE-","",$solicompra);

        if($texto > 0){
            $idParaDescarga = $texto;
        }else{
            $idParaDescarga = 'NO APLICA.';
        }

    }else{
        $idParaDescarga = 'NO APLICA.';
    }

    $sqlArchivo = "SELECT count(*) as total FROM archivos_cotizacion_inventario WHERE idArchivo = '".$idParaDescarga."'";
    $resArchivo = mysqli_query($conexion,$sqlArchivo);
    while($rowArchivo = $resArchivo->fetch_assoc()){
        $total = $rowArchivo['total'];
        $row['total'] = $total;
    }

    $valor = preg_replace('/^N°:\s*/', '', $solicompra);

//   $finalURLPDF = obtenerUrlPdfOrdenCompra($valor);



    // Agregar la fila modificada al array de resultados
    $row['solicompra'] = '';
//   $row['ocUrlPdf'] = $finalURLPDF;
    $row['ocUrlPdf'] = '';
    $row['rutProveedor'] = $rutProveedor;
    $row['nomProveedor'] = $nomProveedor;
    $row['equipoInventario'] = $equipoInventario2;
    $row['marcaInventario'] = $marcaInventario2;
    $row['modeloInventario'] = $modeloInventario2;
    $row['cantidadInventario'] = $cantidadInventario2;
    $row['serieInventario'] = $serieInventario;
    $row['unidad'] = $unidad2;
    $row['fechaInventario'] = $fechaInventario2;
    $row['nomCategoria'] = $getNomCategoriaEquipamiento2;
    $row['fechaInventario'] = $fechaInventario2;
    $row['valorUnitario'] = $valorUnitario2;
    $row['idParaDescarga'] = $idParaDescarga;
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
ob_clean(); // Limpia el búfer por si algo fue impreso antes

echo json_encode(['data' => $datos]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

exit;
?>