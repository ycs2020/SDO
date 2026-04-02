<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('funciones.php');
include_once('conexion.php');

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

date_default_timezone_set('America/Santiago');

if (isset($_POST['addNomCategoria'], $_POST['idCategoria'])) {

    $addNomCategoria = trim($_POST['addNomCategoria']);
    $idCategoria = $_POST['idCategoria'];

    if(!empty($addNomCategoria) && !empty($idCategoria) && $idCategoria > 0){

        $existeSubcategoria = existeSubcategoria($addNomCategoria,$idCategoria);

        // echo $existeSubcategoria;
        if($existeSubcategoria == 0){
            $query = "INSERT INTO subcategorias(idSubcategoria,nomSubcategoria,idCategoria)";
            $query .=  "VALUES (NULL,'" . $addNomCategoria . "','" . $idCategoria . "')";
            $res = mysqli_query($conexion, $query);

            if ($res) {
                header('Location: ../'.$ruta.'/bitacora-listado.php?exitoAgregarCategoria=true');
            } else {
                header('Location: ../'.$ruta.'/bitacora-listado.php?errorAgregarCategoria=true');
            }
        }else{
            header('Location: ../'.$ruta.'/bitacora-listado.php?existeCategoria=true');
        }
    }else{
        header('Location: ../'.$ruta.'/bitacora-listado.php?errorAgregarCategoria=true');
    }

} else {
    header('Location: ../'.$ruta.'/bitacora-listado.php?errorAgregarCategoria=true');
}

mysqli_close($conexion);
