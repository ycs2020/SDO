<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
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
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroMemo = $_POST['numeroMemo'];
    $nombreProceso = $_POST['nombreProceso'];
    $cargo = $_POST['cargo'];
    $tipo = $_POST['tipo'];
    $departamentos = $_POST['departamentos'];
    $presupuesto = $_POST['presupuesto'];
    $encargado = $_POST['encargado'];
    $estado = $_POST['estado'];
    $fechaIngreso = date('Y-m-d H:i:s');
    $idlicitacion = $_POST['idlicitacion'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaTermino = $_POST['fechaTermino'];

    if(empty($fechaTermino)){
        $fechaTermino = '0000-00-00 00:00:00';
    }
    if(empty($fechaInicio)){
        $fechaInicio = "0000-00-00 00:00:00";
    }

    if(empty($presupuesto)){
        $presupuesto = 0;
    }

    $ano = date('Y');

    $sql = "INSERT INTO `licitaciones`(`id`, `numeroMemo`, `nombreProceso`,`unidad`, `cargo`, `tipo`, `presupuesto`, `encargado`, `estado`, `fechaIngreso`,idLicitacion,fechaInicio,fechaTermino,folio) 
    VALUES (NUll,'$numeroMemo','$nombreProceso','$departamentos','$cargo','$tipo','$presupuesto','$encargado','$estado','$fechaIngreso','$idlicitacion','$fechaInicio','$fechaTermino','NULL')";

    $res = mysqli_query($conexion,$sql);


    if($res){
        $id = mysqli_insert_id($conexion);

        $codigoTexto = "LIC-".$ano.'-'.$id;

        $sqlUpdate = "UPDATE `licitaciones` SET `folio`='$codigoTexto' WHERE id = $id";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);

        if($resUpdate){

            if (!empty($_FILES["archivos"])) {
                $count = 0;
                foreach ($_FILES['archivos']['tmp_name'] as $tmpFilee) {
                    if (is_uploaded_file($tmpFilee)) {
                        $count++;
                    }
                }
                if ($count > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $tmp_name = $_FILES["archivos"]["tmp_name"][$i];
                        $name = $_FILES["archivos"]["name"][$i];
                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo "Vacios";
                        }else{
                            $directorio = "../sdo-licitacion-adjuntos/" . $codigoTexto. '/';
                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777, true);
                            }
                            $dir = opendir($directorio);

                            $rutaArchivo = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);
                            if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                chmod($rutaArchivo, 0777);
                                $queryArchivos = "INSERT INTO archivos_licitacion_sdo(id,nomArchivo,rutaArchivo, idLicitacion,folio) 
                                VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $id . "','".$codigoTexto."')";
                                $resArchivos = mysqli_query($conexion, $queryArchivos);
                                if (!$resArchivos) {
                                    $errores[] = "Error al insertar el archivo $name en la base de datos.";
                                }
                            } else {
                                $errores[] = "Error al mover el archivo $name.";
                            }
                        }
                    }
                    if (empty($errores)) {
                        header('Location: ../' . $ruta . '/licitaciones_admin.php?exitoIngresarConArchivo=true');
                        exit(); // Aseguramos que no se ejecute el código siguiente
                    } else {
                        // Mostrar los errores si hay alguno
                        foreach ($errores as $error) {
                            echo $error . "<br>"; // Puedes mostrar los errores o hacer algo con ellos
                        }
                        header('Location: ../' . $ruta . '/licitaciones_admin.php?errorIngresarConArchivo=true');
                        exit();
                    }
                }
            }

            header('Location: ../'. $ruta .'/licitaciones_admin.php?exitoLicitacionSinArchivo=true');

        }else{
            header('Location: ../'. $ruta .'/licitaciones_admin.php?errorLicitacion=true');
        }
    }else{
        header('Location: ../'. $ruta .'/licitaciones_admin.php?errorLicitacion=true');
    }
}else{
    header('Location: ../'. $ruta .'/licitaciones_admin.php?errorLicitacion=true');
}