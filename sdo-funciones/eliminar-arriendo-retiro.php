<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


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
    header('Location: ../sdo-flujo/sdo-flujo/login_index.php?errorSesion=true');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $idSoli = $_POST['idSoli'];
    $codigo = $_POST['codigo'];
    $idDepa  = $_POST['idDepartamento'];
    $idSolicitante = $_POST['idSolicitante'];
    $fechaIngreso = $_POST['fechaIngreso'];

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

        
        //Eliminada estado Herramienta
        $sqlDeleteEstado = "DELETE FROM historial_estado_herramienta WHERE idHerramienta = ${idHerramienta} AND tipoActualizacion = 'Arriendo'";
        $resDeleteEstado = mysqli_query($conexion, $sqlDeleteEstado);


        ?>
            <input type="text" value="<?php echo $herramienta['idHerramienta']?>">
        <?php
    }

    $selectMaterial = "SELECT * FROM material_retirado WHERE idSolicitud  = ${idSoli}";
    $resSelectMaterial = mysqli_query($conexion, $selectMaterial);


    foreach($resSelectMaterial as $indice => $material){

        $cantidadSacar = $material['cantidadMaterial'];
        $idMaterial = $material['idMaterial'];
        $stockActual = $material['stockActual'];

        //$nuevaCantidad = $stockActual +  $cantidadSacar;

        $mate = "SELECT * FROM pañol_materiales WHERE idMaterial = ${idMaterial}";
        $resMaterial = mysqli_query($conexion, $mate);

        while($row = $resMaterial->fetch_assoc()){

            $cantidadActual = $row['cantMaterial'];

        }

        $nuevaCantidad = $cantidadActual + $cantidadSacar;

        $updateMaterial = "UPDATE pañol_materiales SET cantMaterial = ${nuevaCantidad} WHERE idMaterial = ${idMaterial}";
        $res1 = mysqli_query($conexion, $updateMaterial);
        
        //Eliminada estado Material
        $sqlDeleteEstadoMate = "DELETE FROM historial_estado_material WHERE idMaterial = ${idMaterial} AND tipoActualizacion = 'Retiro'";
        $resDeleteMate = mysqli_query($conexion, $sqlDeleteEstadoMate);

        ?>
            <input type="text" value="<?php echo $material['idMaterial']?>">
        <?php
    }

    if($res || $res1){

        $estadoSolicitud = "ELIMINADA";

        //para ingresar los datos a tabla historial
        $insertHistorial = "INSERT INTO solicitudes_arriendo_retiro_finalizadas (idSoli,codSolicitud, fechaSolicitud, idSolicitante,idDepaSolicitante ,estadoSolicitud)  
        VALUES ('". $idSoli . "','". $codigo . "','". $fechaIngreso . "', '". $idSolicitante . "','". $idDepa . "','". $estadoSolicitud . "')";       
        $res1 = mysqli_query($conexion, $insertHistorial);

        $queryEliminar = "DELETE FROM solicitudes_arriendo_retiro WHERE idSoli = ${idSoli}";
        $resQueryEliminar = mysqli_query($conexion, $queryEliminar);

        header('Location: ../'. $ruta  .'/listado-arriendo-retiro.php?eliminado=true');


    }else{
        header('Location: ../'. $ruta  .'/listado-arriendo-retiro.php?errorEliminar=true');
    }


    
    

  

}