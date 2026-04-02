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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idEquipoCambiar = $_POST['idEquipoCambiar'];

    if ($idEquipoCambiar == '' || $idEquipoCambiar < 0 || empty($idEquipoCambiar)) {
    } else {

        if (!empty($_FILES['imagenCategoriaEdita']['name'])) {

            $rutaCarpeta = "../archivos-equipamiento/";
            $nombreCarpeta = "Equipo-".$idEquipoCambiar;

            // Verificar si la carpeta existe o crearla
            if (!is_dir($rutaCarpeta . $nombreCarpeta)) {
                mkdir($rutaCarpeta . $nombreCarpeta, 0777, true);
                echo "Carpeta creada exitosamente.";
            } else {
                echo "La carpeta ya existe.";
            }

            // echo '';

            // Procesar la imagen subida
            if (isset($_FILES['imagenCategoriaEdita'])) {
                $nombreImagen = $_FILES['imagenCategoriaEdita']['name'];
                $rutaImagen = $rutaCarpeta . $nombreCarpeta . '/' . $nombreImagen;

                if (move_uploaded_file($_FILES['imagenCategoriaEdita']['tmp_name'], $rutaImagen)) {
                    echo "La imagen se ha subido correctamente.";
                } else {
                    echo "Error al subir la imagen.";
                }
            }

            $actualizaConImagen = "UPDATE  categorias_equipos SET ru WHERE idCategoria = $idCategoria";
            $respuesta = mysqli_query($conexion, $actualizaConImagen);
        } else {
            $actualizaSinImagen = "UPDATE  categorias_equipos SET  nomCategoria = '$nombreCategoria',  idDepartamento = '$departamentoCategoria'  WHERE idCategoria = $idCategoria";
            $respuesta = mysqli_query($conexion, $actualizaSinImagen);
        }


        if ($respuesta && mysqli_affected_rows($conexion) > 0) {
            header('Location: ../' . $ruta . '/equipamiento_hbqp.php?exitoActualizarCategoria=true;');
        } else {
            header('Location: ../' . $ruta . '/equipamiento_hbqp.php?errorActualizarCategoria=true');
        }
    }
}
