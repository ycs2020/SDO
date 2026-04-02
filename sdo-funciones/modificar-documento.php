<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = '';

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

if(isset($_POST['idRecepcion'])){
    $idRecepcion = $_POST['idRecepcion'];
    $fechaRecepcion = $_POST['fechaRecepcion'];
    $nomRecepcion = $_POST['nomRecepcion'];
    $fechaEmisor = $_POST['fechaEmisor'];
    $nomEmisor = $_POST['nomEmisor'];
    $unidadEmisor = $_POST['unidadEmisor'];
    $selectMetodo = $_POST['selectMetodo'];
    $email = $_POST['email'];
    $selectControl = $_POST['selectControl'];
    $estado = $_POST['estado'];
    $fechaIngresoDocu = $_POST['fechaIngresoDocu'];
    $estadoOriginal = $_POST['estadoOriginal'];


    $sql1 = "SELECT * FROM recepcion_documento WHERE idRecepcion = $idRecepcion";
    $res1 = mysqli_query($conexion,$sql1);
    while($row1 = $res1 ->fetch_assoc()){
        $nomRecepcion1 = $row1['nomRecepcion'];
        $fechaRecepcion1 = $row1['fechaRecepcion'];
        $unidadEmisor1 = $row1['unidadEmisor'];
        $nomEmisor1 = $row1['nomEmisor'];
        $fechaEmisor1 = $row1['fechaEmisor'];
        $metodoEmisor = $row1['metodoEmisor'];
        $correo = $row1['correo'];
        $asignadoRecep = $row1['asignadoRecepcion'];
        $estado1 = $row1['estado'];
    }

    $fecha0 = str_replace('T',' ',$fechaEmisor);

    $fecha2 = $fecha0.':00';

    $fecha = str_replace('T',' ',$fechaRecepcion);

    $fecha1 = $fecha.':00';

    if($nomRecepcion == $nomRecepcion1){
        $nomRecepcionObs = 'Nombre Recepcion: NO';
    }else{
        $nomRecepcionObs = 'Nombre Recepcion: SI';
    }

    if($fecha1 == $fechaRecepcion1){
        $fechaRecepcionObs = 'Fecha de Recepcion: NO';
    }else{
        $fechaRecepcionObs = 'Fecha de Recepcion: SI';
    }

    if($unidadEmisor == $unidadEmisor1){
        $unidadEmisorObs = 'Unidad del Emisor: NO';
    }else{
        $unidadEmisorObs = 'Unidad del Emisor: SI';
    }

    if($nomEmisor == $nomEmisor1){
        $nomEmisorObs = 'Nombre Emisor: NO';
    }else{
        $nomEmisorObs = 'Nombre Emisor: SI';
    }

    if($fecha2 == $fechaEmisor1){
        $fechaEmisorObs = 'Fecha de Emision: NO';
    }else{
        $fechaEmisorObs = 'Fecha de Emision: SI';
    }

    if($metodoEmisor == $selectMetodo){
        $metodoEmisorObs = 'Metodo de Emision: NO';
    }else{
        $metodoEmisorObs = 'Metodo de Emision: SI';
    }

    if($correo == $email){
        $correoObs = 'Email: NO';
    }else{
        $correoObs = 'Email: SI';
    }

    if($asignadoRecep = $selectControl){
        $asignadoRecepObs = 'Personal Asignado: NO';
    }else{
        $asignadoRecepObs = 'Personal Asignado: SI';
    }

    if($estado == $estado1){
            $estadoObs = 'Estado: NO';
    }else{
        $estadoObs = 'Estado: SI';
    }





    if($nomRecepcion == $nomRecepcion1 && $fecha1 == $fechaRecepcion1 && $unidadEmisor == $unidadEmisor1 && $nomEmisor == $nomEmisor1 && $fecha2 == $fechaEmisor1 && $metodoEmisor == $selectMetodo && $correo == $email && $asignadoRecep = $selectControl && $estado == $estado1){
        $detalleObservacion = 'No se han hecho modificaciones';
    }else{
        $detalleObservacion = 'Se ha modificado la siguiente informacion: '.'<br>'.$nomRecepcionObs.'<br>'.$fechaRecepcionObs.'<br>'.$unidadEmisorObs.'<br>'.$nomEmisorObs.'<br>'.$fechaEmisorObs.'<br>'.$metodoEmisorObs.'<br>'.$correoObs.'<br>'.$asignadoRecepObs.'<br>'.$estadoObs;
    }

    $now = date('Y-m-d H:i:s');

    $sqlObs1 = "INSERT INTO recepcion_observacion (idObsRecepcion,detalleObservacion,fechaObs,nomCreador,idRecepcion,estado)
    VALUES (NULL,'".$detalleObservacion."','".$now."','".$nomUsuario."',$idRecepcion,'".$estado."')";
    $resObs1 = mysqli_query($conexion,$sqlObs1);


    $sql = "UPDATE recepcion_documento SET nomRecepcion = '$nomRecepcion', fechaRecepcion = '$fechaRecepcion', unidadEmisor = '$unidadEmisor', nomEmisor = '$nomEmisor', fechaEmisor = '$fechaEmisor', metodoEmisor = '$selectMetodo', correo = '$email', asignadoRecepcion = '$selectControl', estado = '$estado' WHERE idRecepcion = $idRecepcion";
    $res = mysqli_query($conexion,$sql);


    if($res){


        header('Location: ../'.$ruta.'/listar_recepcion_documento.php?exitoModificacionDocumento=true');
    }else{
        header('Location: ../'.$ruta.'/listar_recepcion_documento.php?errorModificacionDocumento=true');
    }
}else{
    header('Location: ../'.$ruta.'/listar_recepcion_documento.php?errorModificacionDocumento=true');
}