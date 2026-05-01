<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Preparamos el encabezado para respuesta JSON
header('Content-Type: application/json');

// VARIABLES DE SESSION
$idUsuario = $_SESSION['idUsuario'] ?? null;
$rolUsuario = $_SESSION['rolUsuario'] ?? '';
$rolUsuario2 = strtolower($rolUsuario);

if (!$idUsuario) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['idEquipo']) && !empty($_POST['idEquipo'])) {

        // Limpiamos los datos de entrada
        $idEquipo = mysqli_real_escape_string($conexion, $_POST['idEquipo']);
        $idCategoria = mysqli_real_escape_string($conexion, $_POST['idCategoria']);
        $opcionEstado = mysqli_real_escape_string($conexion, $_POST['opcionEstado']);
        $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
        $nombreEquipo = mysqli_real_escape_string($conexion, $_POST['nombreEquipo']);

        // ACTUALIZAMOS EL CAMBIO EN LA TABLA PRINCIPAL
        $sqlUpdate = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $idEquipo";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if ($resUpdate) {
            // COMPRUEBA REGISTRO OBSERVACIÓN
            if (compruebaRegistroObservacion($idEquipo) == "Si") {
                mysqli_query($conexion, "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $idEquipo");
            }

            // COMPRUEBA REGISTRO FALLA
            if (compruebaRegistroFalla($idEquipo) == "Si") {
                mysqli_query($conexion, "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $idEquipo");
            }

            // LÓGICA DE INSERCIÓN EN HISTORIALES
            if ($opcionEstado == 'Fuera de Servicio') {
                $sqlFalla = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria, nomEquipo) 
                             VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria, '$nombreEquipo')";
                mysqli_query($conexion, $sqlFalla);
            }

            // INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS (PRINCIPAL Y RESPALDO)
            $sqlHistorial = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria, nombreEquipo) 
                             VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria, '$nombreEquipo')";
            mysqli_query($conexion, $sqlHistorial);

            $sqlRespaldo = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria, nombreEquipo) 
                            VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria, '$nombreEquipo')";
            $resFinal = mysqli_query($conexion, $sqlRespaldo);

            if ($resFinal) {
                echo json_encode(['status' => 'success', 'message' => 'Estado actualizado correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al registrar en el historial']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la tabla de equipamiento']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros (ID Equipo)']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}