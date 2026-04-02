<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM informes_edan ORDER BY fecha DESC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $id = $row["id"];
    $fecha = $row["fecha"];
    $fechaTermino = $row["fechaTermino"];
    $folio = $row["folio"];
    $nomCreador = $row["nomCreador"];
    $correo_enviado = $row["correo_enviado"];
    $fechaEnvioCorreo = $row["fechaEnvioCorreo"];

    if (empty($fecha)) {
        $fechaCreacion = 'Sin Informacion';
    } else {
        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fecha)));
        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fecha)));

        $fechaCreacion = $inicioFecha . '<br>' . $inicioHora . ' hrs.';
    }

    if (empty($fechaTermino)) {
        $fechaTermino2 = 'Sin Informacion';
    } else {
        $terminoFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaTermino)));
        $terminoHora = date('H:i', strtotime(str_replace('/', '-', $fechaTermino)));

        $fechaTermino2 = $terminoFecha . '<br>' . $terminoHora . ' hrs.';
    }



    $row['id'] = $id;
    $row['fecha'] = $fecha;
    $row['fechaTermino'] = $fechaTermino;
    $row['fecha2'] = $fechaCreacion;
    $row['fechaTermino2'] = $fechaTermino2;
    $row['folio'] = $folio;
    $row['nomCreador'] = $nomCreador;
    $row['correo_enviado'] = $correo_enviado;
    $row['fechaEnvioCorreo'] = $fechaEnvioCorreo;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>