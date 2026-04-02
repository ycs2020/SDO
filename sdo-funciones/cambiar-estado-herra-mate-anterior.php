<?php 

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mail = new PHPMailer(true);

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
    header('Location: ../sdo-flujo/sdo-flujo/login_index.php?errorSesion=true');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    
    $idSoli = $_POST['idSoli'];
    $nuevoEstado = $_POST['nuevoEstado'];
    $observaciones = $_POST['observaciones'];
    $codigo = $_POST['codigo'];
    $idDepa  = $_POST['idDepa'];
    $idSolicitante = $_POST['idSolicitante'];
    $fechaIngreso = $_POST['fecha'];



    //CAMBIA ESTADO

    if($nuevoEstado == 'Pendiente'){

        $estado = 'Devolución Pendiente';

        $sql = "UPDATE solicitudes_arriendo_retiro SET estadoSolicitud = '${estado}'  , observaciones = '${observaciones}' WHERE idSoli = ${idSoli} " ;
        $res = mysqli_query($conexion, $sql);

        
        header('Location: ../'. $ruta .'/listado-arriendo-retiro.php?inconclusa=true');
        
    }
 
    if($nuevoEstado == 'Completado'){
        
  
    
        // var_dump($fechaIngreso);
        // exit;
       
    
        $selectHerra = "SELECT * FROM herra_en_arriendo WHERE idSolicitud = ${idSoli}";
        $resSelectHerra = mysqli_query($conexion, $selectHerra);
    
    
        foreach($resSelectHerra as $indice => $herramienta){
    
            $cantidadSacar = $herramienta['cantidadHerramienta'];
            $idHerramienta = $herramienta['idHerramienta'];
            $stockActual = $herramienta['stockActual'];
    
            //$nuevaCantidad = $stockActual +  $cantidadSacar;
    
            $herra = "SELECT * FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
            $resHerra = mysqli_query($conexion, $herra);
    
            while($row = $resHerra->fetch_assoc()){
    
                $cantidadActual = $row['cantHerramienta'];
    
            }
    
            $nuevaCantidad = $cantidadActual + $cantidadSacar;
    
            $updateHerra = "UPDATE pañol_herramientas SET cantHerramienta = ${nuevaCantidad} WHERE idHerramienta = ${idHerramienta}";
            $res = mysqli_query($conexion, $updateHerra);
    
            ?>
                <input type="text" value="<?php echo $herramienta['idHerramienta']?>">
            <?php
        }
    
        // $selectMaterial = "SELECT * FROM material_retirado WHERE idSolicitud  = ${idSoli}";
        // $resSelectMaterial = mysqli_query($conexion, $selectMaterial);
    
    
        // foreach($resSelectMaterial as $indice => $material){
    
        //     $cantidadSacar = $material['cantidadMaterial'];
        //     $idMaterial = $material['idMaterial'];
        //     $stockActual = $material['stockActual'];
    
        //     //$nuevaCantidad = $stockActual +  $cantidadSacar;
    
        //     $mate = "SELECT * FROM pañol_materiales WHERE idMaterial = ${idMaterial}";
        //     $resMaterial = mysqli_query($conexion, $mate);
    
        //     while($row = $resMaterial->fetch_assoc()){
    
        //         $cantidadActual = $row['cantMaterial'];
    
        //     }
    
        //     $nuevaCantidad = $cantidadActual + $cantidadSacar;
    
        //     $updateMaterial = "UPDATE pañol_materiales SET cantMaterial = ${nuevaCantidad} WHERE idMaterial = ${idMaterial}";
        //     $res1 = mysqli_query($conexion, $updateMaterial);
    
        //     ?>
        <!-- //         <input type="text" value="<?php echo $material['idMaterial']?>"> -->
        //     <?php
        // }
    
        if($res || $res1){
    
            $estadoSolicitud = "COMPLETADA";
    
            //para ingresar los datos a tabla historial
            $insertHistorial = "INSERT INTO solicitudes_arriendo_retiro_finalizadas (idSoli,codSolicitud, fechaSolicitud, idSolicitante,idDepaSolicitante ,estadoSolicitud, observaciones)  
            VALUES ('". $idSoli . "','". $codigo . "','". $fechaIngreso . "', '". $idSolicitante . "','". $idDepa . "','". $estadoSolicitud . "','". $observaciones . "')";       
            $res1 = mysqli_query($conexion, $insertHistorial);
    
    
            $queryEliminar = "DELETE FROM solicitudes_arriendo_retiro WHERE idSoli = ${idSoli}";
    
            // var_dump($queryEliminar);
            // exit;
            $resQueryEliminar = mysqli_query($conexion, $queryEliminar);
    
            header('Location: ../'. $ruta .'/listado-arriendo-retiro.php?exito=true');
    
    
        }else{
            header('Location: ../'. $ruta .'/listado-soli-ticket.php?errorEliminar=true');
        }

        
    


    }
   

    

}