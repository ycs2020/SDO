<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i:s', time());

if(isset($_POST['cantidadFilas'])){
    $cantidadFilas = $_POST['cantidadFilas'];
    for($i=1;$i <= $cantidadFilas;$i++){
        $nomFuncionario = $_POST['nomFuncionario'.$i];
        $rutFuncionario = $_POST['rutFuncionario'.$i];
        $cargoFuncionario = $_POST['cargoFuncionario'.$i];
        $tipoCredencial = $_POST['tipoCredencial'.$i];
        $cargoFuncionario = $_POST['cargoFuncionario'.$i];
        $codigoTarjeta = $_POST['codigoTarjeta'.$i];
        $unidadFuncionario = $_POST['unidadFuncionario'.$i];
        $verificarRUT = verificarYFormatearRUT($rutFuncionario);
        if($verificarRUT){
            $sql = "INSERT INTO `credenciales_personal`(`idCredencial`, `rutCredencial`, `nomCredencial`, `cargoCredencial`, `tipoCredencial`, `unidadCredencial`, `codigoCredencial`, `fechaCreacionCredencial`, `nomCreadorCredencial`) 
        VALUES (NULL,'$rutFuncionario','$nomFuncionario','$cargoFuncionario','$tipoCredencial','$unidadFuncionario','$codigoTarjeta','$now','$nomUsuario')";
            $res = mysqli_query($conexion,$sql);
            if($res){
                header('Location: ../sdo-usuariosPersonal/credenciales_administracion.php?exito=true');
            }else{
                header('Location: ../sdo-usuariosPersonal/credenciales_administracion.php?error=true');
            }
        }else{
            header('Location: ../sdo-usuariosPersonal/credenciales_administracion.php?error1=true');
        }

    }
}else{
    header('Location: ../sdo-usuariosPersonal/credenciales_administracion.php?error2=true');
}