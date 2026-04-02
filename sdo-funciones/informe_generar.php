<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['titulo'], $_POST['fecha'])){
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha'];
    $desarrollo = $_POST['desarrollo'];
    $conclusion = $_POST['conclusion'];
    $nomUsuario = $_POST['nomUsuario'];
    $departamento = $_POST['departamento'];
    $introduccion = $_POST['introduccion'];
    $codigo = 'INF-';


    $sql = "INSERT INTO `informe_sdo`(`id`, `nomCreador`,folio,introduccion, `titulo`, `desarrollo`, `conclusion`, `departamento`, `fecha`) 
    VALUES (NULL,'$nomUsuario','NULL','$introduccion','$titulo','$desarrollo','$conclusion',$departamento,'$fecha')";
    $res = mysqli_query($conexion,$sql);
    if($res){

        $id = mysqli_insert_id($conexion);

        if($departamento == 1){
            $codigoTexto = $codigo.'CC-'.$id;
        }else if($departamento == 2){
            $codigoTexto = $codigo.'INDUS-'.$id;
        }else if($departamento == 3){
            $codigoTexto = $codigo.'CLIMA-'.$id;
        }else if($departamento == 4){
            $codigoTexto = $codigo.'ELEC-'.$id;
        }else if($departamento == 5){
            $codigoTexto = $codigo.'INFRA-'.$id;
        }else if($departamento == 6){
            $codigoTexto = $codigo.'IC-'.$id;
        }else if($departamento == 7){
            $codigoTexto = $codigo.'LOG-'.$id;
        }else if($departamento == 8){
            $codigoTexto = $codigo.'MOVIL-'.$id;
        }else if($departamento == 9){
            $codigoTexto = $codigo.'IO-'.$id;
        }else if($departamento == 10){
            $codigoTexto = $codigo.'AC-'.$id;
        }else if($departamento == 12){
            $codigoTexto = $codigo.'INCE-'.$id;
        }else{
            $codigoTexto = $codigo.'SDO-'.$id;
        }

        $sqlUpdate = "UPDATE `informe_sdo` SET `folio`='$codigoTexto' WHERE id = $id";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);


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
                        $directorio = "../sdo-informes-adjuntos/" . $codigoTexto. '/';
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
                            $queryArchivos = "INSERT INTO archivos_informe_sdo(id,nomArchivo,rutaArchivo, idInfo,folio) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $id . "','".$codigoTexto."')";
                            $resArchivos = mysqli_query($conexion, $queryArchivos);
                            if (!$resArchivos) {
                                $errores[] = "Error al insertar el archivo $name en la base de datos.";
                            }
                        } else {
                            $errores[] = "Error al mover el archivo $name.";
                        }
                    }
                }
                // Si no hay errores, redirigir al usuario
                if (empty($errores)) {
                    header('Location: ../' . $rutaDireccion . '/informe_listado.php?exitoIngresarConArchivo=true');
                    exit(); // Aseguramos que no se ejecute el código siguiente
                } else {
                    // Mostrar los errores si hay alguno
                    foreach ($errores as $error) {
                        echo $error . "<br>"; // Puedes mostrar los errores o hacer algo con ellos
                    }
                    header('Location: ../' . $rutaDireccion . '/informe_listado.php?errorIngresarConArchivo=true');
                    exit();
                }
            }
        }
        header('Location: ../'.$rutaDireccion.'/informe_listado.php?exitoIngresarSinArchivo=true');

    }else{
        header('Location: ../'.$rutaDireccion.'/informe_listado.php?errorIngresar=true');
    }
}else{
    header('Location: ../'.$rutaDireccion.'/informe_listado.php?errorIngresar=true');
}
