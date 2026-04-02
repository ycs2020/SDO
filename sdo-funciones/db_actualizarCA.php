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

if (isset($_FILES['archivo_db'],$_POST['nombreDatos'])) {
    // Obtener detalles del archivo subido
    $archivo = $_FILES['archivo_db'];
    $rutaTemporal = $archivo['tmp_name'];
    $nombreArchivo = $archivo['name'];
    $tipoArchivo = $archivo['type'];
    $tamañoArchivo = $archivo['size'];

    $nomDatos = $_POST['nombreDatos'];

    $nomDatosArray = [
        'ACM_owner',
        'ACS_DOOR_GROUP',
        'ACS_DOOR_GROUP_DOOR',
        'ACS_DOOR_GROUP_USER',
        'Department_table',
        'PersonInter',
        'DepartmentInter'
    ];

    if(in_array($nomDatos, $nomDatosArray)){

        // Verificar si es un archivo .db
        $ext = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        if ($ext !== 'db') {
            $mensajeAlerta = "Error en Extension de Archivo";
            $icon = "Error";
        }

        // Ruta de destino para almacenar temporalmente el archivo .db subido
        $rutaDestino = 'cargaDatos/' . basename($nombreArchivo);

        // Mover el archivo subido a la carpeta "cargaDatos"
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
//            echo "El archivo ha sido subido exitosamente.<br>";

            // Conectar a la base de datos SQLite
            if (file_exists($rutaDestino)) {


                $db = new SQLite3($rutaDestino);

                // Consulta SQL (ajústala según tu tabla y datos)
                if($nomDatos == 'PersonInter'){
                    $query = "SELECT * FROM Person";
                }else if($nomDatos == 'DepartmentInter'){
                    $query = "SELECT * FROM Department";
                }else{
                    $query = "SELECT * FROM $nomDatos";
                }

                // Ejecutar la consulta
                $result = $db->query($query);

                $contadorFilas = 0;
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $contadorFilas++;
                }

                if($contadorFilas > 0) {

                    if($nomDatos == 'PersonInter'){
                        $sqlBorrarCarpeta = "TRUNCATE TABLE intercom_users";
                    }else if($nomDatos == 'DepartmentInter'){
                        $sqlBorrarCarpeta = "TRUNCATE TABLE intercom_department";
                    }else{
                        $sqlBorrarCarpeta = "TRUNCATE TABLE $nomDatos";
                    }

                    // Ejecutar el borrado en MySQL
                    $resBorrarCarpeta = mysqli_query($conexion, $sqlBorrarCarpeta);

                    if ($resBorrarCarpeta) {

                        $correcto = 0;
                        $malo = 0;

                        // Iterar sobre los resultados
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            // Crear la cadena de valores para la consulta (se separan por comas)
                            $valores = implode("', '", array_map([$conexion, 'real_escape_string'], $row));

                            // Consulta de inserción en MySQL, sin especificar los campos (orden idéntico)
                            if($nomDatos == 'PersonInter'){
                                $sqlInsert = "INSERT INTO intercom_users VALUES ('$valores')";
                            }else if($nomDatos == 'DepartmentInter'){
                                $sqlInsert = "INSERT INTO intercom_department VALUES ('$valores')";
                            }else{
                                $sqlInsert = "INSERT INTO $nomDatos VALUES ('$valores')";
                            }

                            if ($conexion->query($sqlInsert)) {
                                $correcto++;
                            } else {
                                $malo++;
                            }
                        }

                        // Cerrar la conexión
                        $db->close();

                        // Eliminar el archivo después de usarlo
                        if (unlink($rutaDestino)) {
                            $mensajeAlerta = "$nomDatos : Información Cargada con Exito en Base de Datos.";
                            $icon = "Correcto";
                        } else {
                            $mensajeAlerta = "Información Cargada con Exito. No fue posible eliminar Archivo Subido.";
                            $icon = "Correcto";
                        }

                    } else {
                        $mensajeAlerta = "Error al Eliminar Registros Anteriores.";
                        $icon = "Error";
                    }
                }else{
                    $mensajeAlerta = "No existen Registros para Subir.";
                    $icon = "Error";
                }

            } else {
                $mensajeAlerta = "Archivo DB no existe.";
                $icon = "Error";
            }
        } else {
            $mensajeAlerta = "Error al Mover Archivo Subido.";
            $icon = "Error";
        }
    }else{
        $mensajeAlerta = "Favor Seleccione Nombre de Tabla.";
        $icon = "Error";
    }
} else {
    $mensajeAlerta = "Favor Seleccione Archivo.";
    $icon = "Error";
}

$_SESSION["mensajeAlerta"] = $mensajeAlerta;
$_SESSION["iconAlerta"] = $icon;

header("Location: ../$ruta/control-acceso.php?mensajeVar=true");
