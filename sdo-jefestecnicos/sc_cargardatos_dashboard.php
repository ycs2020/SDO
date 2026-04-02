<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (isset($_POST['argumento'],$_POST['anio'])) {

    $argumento = $_POST['argumento'];
    $anio = $_POST['anio'];
    $depto1 = $_POST['depto1'];
    $depto2 = $_POST['depto2'];

    if (!empty($argumento) && $anio > 0 && !empty($depto1) && !empty($depto2)) {

        if($depto2 == 'vacio'){
            $depto = 'unidadSoliCompra LIKE "'.$depto1.'"';
        }else{

            $depto = 'unidadSoliCompra LIKE "'.$depto1.'" OR unidadSoliCompra LIKE "'.$depto2.'"';
        }


        if($argumento == 'enCurso'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra NOT LIKE '%Solicitud Anulada%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'anuladas'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%Solicitud Anulada%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'porRevisarSDO'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%Solicitada a Secretaria SDO%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'porTramitarSDO'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%Revisada por Secretaría SDO%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'recibidaSDA'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%ENTREGADO A LA S.D.A%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'aprobadaSDA'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%Autorizado por la S.D.A%)' AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'rechazadaSDA'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%Rechazado por la S.D.A%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'abastecimiento'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%DERIVADO A ABASTECIMIENTO%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'panol'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%Recibido por pañol%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'completada'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra LIKE '%Solicitud Completada%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else if($argumento == 'enviadasSDA'){
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo WHERE (".$depto.") AND (estadoSoliCompra NOT LIKE '%Solicitud Anulada%' AND estadoSoliCompra NOT LIKE '%Solicitada a Secretaria SDO%' AND estadoSoliCompra NOT LIKE '%Revisada por Secretaria SDO%') AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }else{
            // Consulta SQL
            $sql = "SELECT * FROM solicitudes_compra_sdo  WHERE (".$depto.") AND (YEAR(fechaSoliCompra) = ".$anio.") ORDER BY fechaSoliCompra DESC";
        }

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
//        $stmt->bind_param("s", $idEquipoAjax);
        $stmt->execute();


// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            date_default_timezone_set('America/Santiago');
            $folioSoliCompra = $row["folioSoliCompra"];
            $personalSoliCompra = $row["personalSoliCompra"];
            $fechaSoliCompra = $row["fechaSoliCompra"];

            if(!empty($fechaSoliCompra)){
                $fechaSoliCompra = strtotime($fechaSoliCompra);

                $fecha = date("d-m-Y",$fechaSoliCompra);
                $hora = date("H:i",$fechaSoliCompra);

                $fechaSoliCompra2 = $fecha.' a las '.$hora.' hrs.';
            }else{
                $fechaSoliCompra2 = 'No Aplica.';
            }

            $folioSoliCompra = mb_convert_case($folioSoliCompra, MB_CASE_UPPER, 'UTF-8');
            $personalSoliCompra = mb_convert_case($personalSoliCompra, MB_CASE_UPPER, 'UTF-8');
            $fechaSoliCompra2 = mb_convert_case($fechaSoliCompra2, MB_CASE_UPPER, 'UTF-8');

            $row['fechaSoliCompra'] = $fechaSoliCompra2;
            $row['folioSoliCompra'] = $folioSoliCompra;
            $row['personalSoliCompra'] = $personalSoliCompra;

            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);
    }

}
?>