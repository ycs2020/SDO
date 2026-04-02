<?php

include 'funciones.php';
include 'conexion.php';

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

$rutaUsuario = "";
// echo '';
if ($rolUsuario2 == "personal") {
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaUsuario = "sdo-jefestecnicos";
    $idDepartamento = $_SESSION['idDepartamento'];
} else if ($rolUsuario2 == "administrador") {
    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaUsuario = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['idPetroleo'])){

        $idMovil = $_POST['idMovil'];
        $idPetroleo = $_POST['idPetroleo'];
        $fechaReporte = $_POST['fechaReporte'];
        $numGuia = $_POST['numGuia'];
        $litros = $_POST['litros'];
        $valorUnitario = $_POST['valorUnitario'];

        $totalSinIva = $litros * $valorUnitario;
        $ivaCalculado = $totalSinIva * 0.19;
        $totalConIva = $totalSinIva + $ivaCalculado;

        echo $idPetroleo . "<br>";
        echo $fechaReporte . "<br>";
        echo $numGuia . "<br>";
        echo $litros . "<br>";
        echo $valorUnitario . "<br>";

        $sql = "UPDATE historial_petroleo_moviles SET fechaRegistro = '$fechaReporte' , numGuia = '$numGuia', cantidadLitros = $litros, valorUnitario = $valorUnitario, totalSinIva = $totalSinIva, IvaCalculado = $ivaCalculado, totalConIva = $totalConIva WHERE idPetroleo = $idPetroleo ";
        echo $sql;
        $res = mysqli_query($conexion, $sql);

        if($res){
            header('Location: ../'.$rutaUsuario.'/visualizarMovil.php?exitoModificar=true&&idMovil=' . $idMovil);
        }else{
            header('Location: ../'.$rutaUsuario.'/visualizarMovil.php?errorModificar=true&&idMovil=' . $idMovil);
        }

    }
}
