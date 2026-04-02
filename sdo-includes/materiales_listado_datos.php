<?php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM producto_solicitud ORDER BY nombre ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $id = $row['id'];
    $nombre = $row['nombre'];
    $unidad = $row['unidad_medida'];
    $cantidad = $row['cantidad'];
    $valor = $row['valor'];
    $imagen = $row['imagen'];
    $enlace = $row['enlace_archivo'];
    $fecha = $row['fecha_creacion'];

    $nombre2 = mb_convert_case($nombre, MB_CASE_UPPER, "UTF-8");
    $unidad2 = mb_convert_case($unidad, MB_CASE_UPPER, "UTF-8");

    if($valor > 0){
        $valor2 = '$' . number_format($valor, 0, '', '.');
    }else{
        $valor2 = "$0";
    }

    $fechaCompleta = 'Fecha Inválida';

    $fechaRaw = $row['fecha_creacion'] ?? '';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $inicioFecha = date('d/m/Y', $timestamp);
            $inicioHora  = date('H:i', $timestamp);
            $fechaCompleta = $inicioFecha . '<br>' . $inicioHora;
        }
    }

//    $fechaValida = (strtotime($fechaPerfil)) ? fechaEspañol2($fechaPerfil) : 'No Aplica';

    $enlaceExisteCurl = enlaceExisteCurl($enlace);

    $buttonEnlace = '';
    if (!empty($enlace) && $enlaceExisteCurl) {
        $buttonEnlace = "
        <a href='$enlace' target='_blank' class='btn btn-sm btn-block bg-primary text-white'>
            <i class='fas fa-eye'></i>
        </a>
    ";
    } else {
        $buttonEnlace = "<span class='text-muted'>Sin Enlace</span>";
    }

    $imagenRuta = $row['imagen']; // Ej: "sdo-material-solic/imagen-23/img_abc.jpg"
    $imagenExiste = file_exists('../' . $imagenRuta); // ruta relativa desde PHP

    if (!empty($imagenRuta) && $imagenExiste) {
        $imagenHTML = "
        <img src='../$imagenRuta' class='img-thumbnail img-zoom-hover' style='max-width: 100px;' alt='Imagen Material'>
    ";
    } else {
        $imagenHTML = "<span class='text-muted'>Sin Imagen</span>";
    }

//    if (!empty($row["idPerfil"]) && $row["idPerfil"] > 0) {
//        $row["botonPerfil"] = '
//        <form style="margin: 0; text-align: center;" action="ccaa_perfiles_protocolo_detalle.php" target="_blank" method="post">
//            <input type="hidden" name="idPerfilCargar" value="' . $row["idPerfil"] . '">
//            <button type="submit" class="btn btn-primary btn-block">
//                <i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar
//            </button>
//        </form>';
//    } else {
//        $row["botonPerfil"] = '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>';
//    }

    $row['id'] = $id;
    $row['nombre'] = $nombre;
    $row['unidad'] = $unidad;
    $row['cantidad'] = $cantidad;
    $row['valor'] = $valor;
    $row['imagen'] = $imagen;
    $row['enlace_archivo'] = $enlace;
    $row['fecha_creacion'] = $fecha;
    $row['nombre2'] = $nombre2;
    $row['unidad2'] = $unidad2;
    $row['fechaCompleta'] = $fechaCompleta;
    $row['buttonEnlace'] = $buttonEnlace;
    $row['imagenHTML'] = $imagenHTML;
    $row['valor2'] = $valor2;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>