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
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else {
    header('Location: ../sdo-flujo/sdo-flujo/login_index.php?errorSesion=true');
}

$headerTextCorrecto = 'Location: ../' . $ruta . '/sistemas-visualizar.php?exitoAgregarEquipo=true';
$headerTextIncorrecto = 'Location: ../' . $ruta . '/sistemas-visualizar.php?errorAgregarEquipo=true';
$headerTextExiste = 'Location: ../' . $ruta . '/sistemas-visualizar.php?existeEquipo=true';

if (isset($_POST['addNomEquipo'], $_POST['categoriaAgregar'])) {

    $addNomEquipo = trim($_POST['addNomEquipo']);
    $categoriaAgregar = $_POST['categoriaAgregar'];

    if(!empty($addNomEquipo) && !empty($categoriaAgregar) && $categoriaAgregar > 0){

        $existeEquipo = existeEquipo($addNomEquipo,$categoriaAgregar);

        $usuarioCreador = $_SESSION['nomUsuario'].' - '.$_SESSION['nomDepartamento'];

        if($existeEquipo == 0){
            $query = "INSERT INTO equipos(idEquipo,nomEquipo,idSubCategoria,forzadoEquipo,forzadoEncendido,forzadoApagado,forzadoVariable,modoAutomatico,isPabellon,equipoPabellon,nomCreador)";
            $query .=  "VALUES (NULL,'" . $addNomEquipo . "','" . $categoriaAgregar . "',0,0,0,0,1,0,0,'" . $usuarioCreador . "')";
            $res = mysqli_query($conexion, $query);

            if ($res) {
                header($headerTextCorrecto);
            } else {
                header($headerTextIncorrecto);
            }
        }else{
            header($headerTextExiste);
        }
    }else{
        header($headerTextIncorrecto);
    }

} else {
    header($headerTextIncorrecto);
}

mysqli_close($conexion);
