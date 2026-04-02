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
}else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    //RECIBIMOS LOS DATOS Y LOS ALMACENAMOS EN VARIABLES
    $idMovil = $_POST['idMovil'];
    $fechaReporte = $_POST['fechaReporte'];
    $numGuia = $_POST['numGuia'];
    $litros = $_POST['litros'];
    $valorUnitario = $_POST['valorUnitario'];
  
    $totalSinIva = $litros * $valorUnitario;
    $ivaCalculado = $totalSinIva * 0.19;
    $totalConIva = $totalSinIva + $ivaCalculado;

    echo $litros . "<br>";
    echo $valorUnitario. "<br>";
    echo $totalSinIva. "<br>";
    echo $ivaCalculado. "<br>";
    echo $totalConIva. "<br>";

    //INSERTAMOS EN LA BASE DE DATOS
    $sql = "INSERT INTO historial_petroleo_moviles (fechaRegistro, numGuia,cantidadLitros,valorUnitario,totalSinIva,IvaCalculado,totalConIva,idMovil)  VALUES ('$fechaReporte', '$numGuia', $litros, $valorUnitario, $totalSinIva, $ivaCalculado, $totalConIva, $idMovil) ";
    $res = mysqli_query($conexion , $sql);

    if($res){
        header('Location: ../'. $ruta .'/visualizarMovil.php?exitoPetroleo=true&&idMovil=' . $idMovil);
    }else{
        header('Location: ../'. $ruta .'/visualizarMovil.php?errorPetroleo=true&&idMovil=' . $idMovil);
    }
}
