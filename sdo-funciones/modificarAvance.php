<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $avances = $_POST['avances'];
    $obs = $_POST['obs'];
    $nomCreador = $_POST['nomCreador'];
    $ida = $_POST['ida'];
       foreach ($ida as $campo1 => $valor1) {
           $valorAvance = $avances[$campo1];
           $valorObs = $obs[$campo1];
           $actualizar = "UPDATE avances_trabajo_general SET detalleObs = '$valorAvance', observacion = '$valorObs' WHERE nomCreador = '$nomCreador' AND idAvance = $valor1";
           $resAcutalizar = mysqli_query($conexion,$actualizar);
       }
       if($resAcutalizar){
           if($tipoUsuario != 'personal'){
               header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoActualizar=true');
           }else{
               header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?exitoActualizar=true');
           }
       }else{
           if($tipoUsuario != 'personal'){
               header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorActualizar=true');
           }else{
               header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorActualizar=true');
           }
       }
}else{
    header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?error=true');
}