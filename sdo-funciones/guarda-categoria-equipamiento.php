<?php
include 'conexion.php';
include 'funciones.php';

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
} else if ($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
} else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreCategoria = $_POST['nombreCategoria'];
    $departamentoCategoria = $_POST['departamentoCategoria'];
    $imagenCategoria = $_FILES['imagenCategoria'];

    $nombreCategoria = mysqli_real_escape_string($conexion, $nombreCategoria);
    $departamentoCategoria = mysqli_real_escape_string($conexion, $departamentoCategoria);

    if ($nombreCategoria == '' || $departamentoCategoria == '' || $imagenCategoria == '') {
    } else {

        $rutaCarpeta = "../sdo-administrador/img/";
        $nombreCarpeta = "equipamiento_hbqp";

        // Verificar si la carpeta existe o crearla
        if (!is_dir($rutaCarpeta . $nombreCarpeta)) {
            mkdir($rutaCarpeta . $nombreCarpeta, 0777, true);
            echo "Carpeta creada exitosamente.";
        } else {
            echo "La carpeta ya existe.";
        }

        // Procesar la imagen subida
        if (isset($_FILES['imagenCategoria'])) {
            $nombreImagen = $_FILES['imagenCategoria']['name'];
            $rutaImagen = $rutaCarpeta . $nombreCarpeta . '/' . $nombreImagen;

            if (move_uploaded_file($_FILES['imagenCategoria']['tmp_name'], $rutaImagen)) {
                echo "La imagen se ha subido correctamente.";
            } else {
                echo "Error al subir la imagen.";
            }
        }

        $insertamosCategoria = "INSERT INTO `categorias_equipos`(`idCategoria`, `nomCategoria`, `imagenCategoria`, `idDepartamento`) VALUES (null,'$nombreCategoria','$rutaImagen',$departamentoCategoria) ";
        $respuesta = mysqli_query($conexion, $insertamosCategoria);

        if ($respuesta && mysqli_affected_rows($conexion) > 0) {
            header('Location: ../' . $ruta . '/equipamiento_hbqp.php?exitoCrearCategoria=true;');
        } else {
            header('Location: ../' . $ruta . '/equipamiento_hbqp.php?errorCrearCategoria=true');
        }
    }
}
