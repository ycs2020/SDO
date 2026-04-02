<?php
// Mostrar todos los errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

// Incluir la conexión a la base de datos y otras funciones si es necesario
include_once 'conexion.php';
include_once 'funciones.php';

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_CL", 'Spanish_Chile', 'Spanish');
setlocale(LC_MONETARY, 'es_CL');

$fechaNow = date('Y-m-d H:i:s');

if (isset($_FILES['archivo_db'])) {
    // Obtener detalles del archivo subido
    $archivo = $_FILES['archivo_db'];
    $rutaTemporal = $archivo['tmp_name'];
    $nombreArchivo = $archivo['name'];
    $tipoArchivo = $archivo['type'];
    $tamañoArchivo = $archivo['size'];

    $nomDatosArray = [
        'ACM_owner',
        'ACS_DOOR_GROUP',
        'ACS_DOOR_GROUP_DOOR',
        'ACS_DOOR_GROUP_USER',
        'Department_table'
    ];

    // Verificar si es un archivo .db
    $ext = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    if ($ext !== 'db') {
        $mensajeAlerta = "Error en Extension de Archivo";
        $icon = "Error";
    } else {
        // Ruta de destino para almacenar temporalmente el archivo .db subido
        $rutaDestino = 'cargaDatos/' . basename($nombreArchivo);

        // Mover el archivo subido a la carpeta "cargaDatos"
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            // Conectar a la base de datos SQLite
            if (file_exists($rutaDestino)) {
                $db = new SQLite3($rutaDestino);

                $correctoTotal = 0;
                $maloTotal = 0;

                // Iterar sobre cada tabla del array
                foreach ($nomDatosArray as $nomDatos) {
                    // Consulta SQL
                    $query = "SELECT * FROM $nomDatos";
                    $result = $db->query($query);

                    $contadorFilas = 0;
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        $contadorFilas++;
                    }

                    if ($contadorFilas > 0) {
                        // Eliminar los registros previos en MySQL
                        $sqlBorrarCarpeta = "TRUNCATE TABLE $nomDatos";
                        $resBorrarCarpeta = mysqli_query($conexion, $sqlBorrarCarpeta);

                        if ($resBorrarCarpeta) {
                            $correcto = 0;
                            $malo = 0;

                            // Insertar los nuevos datos
                            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                                $valores = implode("', '", array_map([$conexion, 'real_escape_string'], $row));
                                $sqlInsert = "INSERT INTO $nomDatos VALUES ('$valores')";

                                if ($conexion->query($sqlInsert)) {
                                    $correcto++;
                                } else {
                                    $malo++;
                                }
                            }

                            // Sumar resultados al total
                            $correctoTotal += $correcto;
                            $maloTotal += $malo;

                        } else {
                            $mensajeAlerta = "Error al Eliminar Registros Anteriores de $nomDatos.";
                            $icon = "Error";
                        }
                    } else {
                        $mensajeAlerta = "$nomDatos: No existen Registros para Subir.";
                        $icon = "Error";
                    }
                }

                // Cerrar la conexión a SQLite
                $db->close();

                // Eliminar el archivo después de usarlo
                if (unlink($rutaDestino)) {
                    $mensajeAlerta = "Información Cargada con Exito: $correctoTotal registros correctos, $maloTotal fallos.";
                    $icon = "Correcto";
                } else {
                    $mensajeAlerta = "Información Cargada. No fue posible eliminar Archivo Subido.";
                    $icon = "Correcto";
                }

            } else {
                $mensajeAlerta = "Archivo DB no existe.";
                $icon = "Error";
            }
        } else {
            $mensajeAlerta = "Error al Mover Archivo Subido.";
            $icon = "Error";
        }
    }
} else {
    $mensajeAlerta = "Favor Seleccione Archivo.";
    $icon = "Error";
}

$_SESSION["mensajeAlerta"] = $mensajeAlerta;
$_SESSION["iconAlerta"] = $icon;

header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
