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

$fecha = strtotime($fechaNow);
$fechaPalabra = fechaEspañol2($fecha);

//$tituloRegistro = 'Reportes de Alarmas BMS '.$fechaPalabra;

// Incluir la clase PHPExcel
require_once '../sdo-excelphp/Classes/PHPExcel.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_csv'],$_POST['controlRegistro'], $_POST['nombreReporte'])) {

    $controlRegistro = $_POST['controlRegistro'];
    $nombreReporte = $_POST['nombreReporte'];

    if(!empty($controlRegistro)){
        $controlRegistro2 = mb_convert_case($controlRegistro,MB_CASE_UPPER,'UTF-8');




        $sqlCreaRegistro = "INSERT INTO bms_alarmas_registro(idRegistro, fechaRegistro, nomRegistro, controlRegistro) VALUES (NULL,'$fechaNow','$nombreReporte','$controlRegistro2')";
        $resCreaRegistro = mysqli_query($conexion,$sqlCreaRegistro);

        if($resCreaRegistro){

            $idRegistroAlarmas = mysqli_insert_id($conexion);

            $fileTmpPath = $_FILES['archivo_csv']['tmp_name'];
            $fileName = $_FILES['archivo_csv']['name'];
            $fileSize = $_FILES['archivo_csv']['size'];
            $fileType = $_FILES['archivo_csv']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Verificar si el archivo es CSV
            if ($fileExtension === 'csv') {
                try {
                    // Abrir el archivo CSV
                    if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
                        // Leer las cabeceras
                        $headers = fgetcsv($handle, 1000, ",");
//
                        function cleanHeader($header) {
                            return trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header));
                        }

// Limpiar encabezados
                        $headers = array_map('cleanHeader', $headers);
//                var_dump($headers);
//                exit();
                        // Validar cabeceras
                        $requiredHeaders = ['Timestamp', 'Source State', 'Ack State', 'Source', 'Alarm Class', 'Priority', 'Normal Time', 'Alarm Data', 'Message Text', 'Notes'];
                        $requiredHeaders = array_map('cleanHeader', $requiredHeaders);
                        if ($headers !== $requiredHeaders) {
                            echo "<pre>";
                            echo "Cabeceras del archivo CSV:\n";
                            print_r($headers);
                            echo "\nCabeceras requeridas:\n";
                            print_r($requiredHeaders);
                            echo "</pre>";
                            exit("Las cabeceras del archivo CSV no coinciden.");
                        }

                        $totalRows = 0;
                        $totalBuenos = 0;
                        $totalMalos = 0;
                        $categoriasBuenas = 0;

                        // Leer las filas del archivo CSV
                        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $totalRows++;

                            // Asignar los valores a variables
                            $timestamp = $row[0];



                            $timestamp = date('Y-m-d H:i:s', strtotime($row[0]));

                            if (validateTimestamp($timestamp)) {
                                $fechaTimestamp = "'".$timestamp."'";
                            } else {
                                $fechaTimestamp = 'NULL';
                            }

                            $sourceState = $row[1];
                            $ackState = $row[2];
                            $source = $row[3];
                            $alarmClass = $row[4];

                            $alarmClass = trim($alarmClass);

                            $priority = $row[5];
                            $normalTime = date('Y-m-d H:i:s', strtotime($row[6]));

                            if (validateTimestamp($normalTime)) {
                                $fechaNormal = "'".$normalTime."'";
                            } else {
                                $fechaNormal = 'NULL';
                            }
                            $alarmData = $row[7];
                            $messageText = $row[8];
                            $notes = $row[9];

                            $sourceState = mysqli_real_escape_string($conexion, $sourceState);
                            $ackState = mysqli_real_escape_string($conexion, $ackState);
                            $source = mysqli_real_escape_string($conexion, $source);
                            $alarmClass = mysqli_real_escape_string($conexion, $alarmClass);
                            $priority = mysqli_real_escape_string($conexion, $priority);
                            $alarmData = mysqli_real_escape_string($conexion, $alarmData);
                            $messageText = mysqli_real_escape_string($conexion, $messageText);
                            $notes = mysqli_real_escape_string($conexion, $notes);

                            // Insertar los datos en la base de datos
                            $sql = "INSERT INTO bms_alarmas_historial 
                            (idHistorial, fechaHistorial, stateHistorial, ackHistorial, sourceHistorial, classHistorial, priorityHistorial, normalTimeHistorial, alarmDataHistorial, mensajeHistorial, notesHistorial, subidaHistorial, idRegistro) 
                            VALUES 
                            (NULL, $fechaTimestamp, '$sourceState', '$ackState', '$source', '$alarmClass', '$priority', $fechaNormal,'$alarmData', '$messageText', '$notes', '$fechaNow', $idRegistroAlarmas)";
                            $res = mysqli_query($conexion, $sql);

                            if ($res) {
                                $totalBuenos++;

                                $isClassAgregada = buscarCategoriaRegistroAlarma($alarmClass);

                                if($isClassAgregada == 0) {
                                    $sqlAgregarClass = "INSERT INTO bms_alarmas_categoria (idCategoria, nomCategoria) VALUES (NULL,'$alarmClass')";

                                    $resAgregarClass = mysqli_query($conexion,$sqlAgregarClass);

                                    if($resAgregarClass){
                                        $categoriasBuenas++;
                                    }
                                }

                            } else {
                                $totalMalos++;
//                        echo $sql.'<br><br>';
                            }
                        }

                        fclose($handle);

                        $varRespuesta = "Se procesaron $totalRows registros: $totalBuenos exitosos y $totalMalos fallidos. Se agregaron $categoriasBuenas categorias nuevas.";
                    } else {
                        throw new Exception("No se pudo abrir el archivo CSV.");
                    }
                } catch (Exception $e) {
                    $varRespuesta = "Error al procesar el archivo CSV: " . $e->getMessage();
                }
            } else {
                $varRespuesta = "Error. El archivo subido no es un archivo CSV.";
            }

        }else{
            $varRespuesta = "Error al Crear Registro de Alarmas.";
        }
    }else{

        $varRespuesta = "Error al Obtener Nombre de Funcionario.";
    }
} else {
    $varRespuesta = "No se ha subido ningún archivo.";
}

$_SESSION['varRespuesta'] = $varRespuesta;
$_SESSION['idRegistro'] = $varRespuesta;
header("Location: ../$ruta/bms_reporte_detalle.php?mensajeVar=true");

//echo $varRespuesta;
?>