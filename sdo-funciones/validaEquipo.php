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
    header('Location: ../sdo-flujo/sdo-flujo/login_index.php?errorSesion=true');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $busqueda = $_POST['busqueda'];
    $opcion = $_POST['opcion'];
    $idFiltra = $_POST['idFiltra'];
    $opcionId = $_POST['opcionId'];

    if($busqueda == '' && $opcion == '' && $idFiltra == '' && $opcionId == ''){
        header('Location: ../'. $ruta .'/consultar-equipo.php?filtrosVacios=true');
    }


    //  var_dump($opcion);
    if($opcion == 'folio'){
        $condicion = "folio";
    }
   
     if($opcion == 'inventario'){
        $condicion = "numInventario";
    }

    if(!empty($idFiltra)){
        $sqlHoja = "SELECT * FROM hoja_de_vida WHERE  idUnico = '$idFiltra' " ;
        $resHoja = mysqli_query($conexion, $sqlHoja);
    }else  if(!empty($opcionId) ){
        $sqlHoja = "SELECT * FROM hoja_de_vida WHERE  idHoja = '$opcionId' " ;
        $resHoja = mysqli_query($conexion, $sqlHoja);
    }  else{
        $sqlHoja = "SELECT * FROM hoja_de_vida WHERE  $condicion = '$busqueda' " ;
        $resHoja = mysqli_query($conexion, $sqlHoja);
    }

    while($rowHoja = $resHoja->fetch_assoc()) {

        $datos = [];

        $datos['idHoja'] = $rowHoja['idHoja'];
        $datos['idUnico'] = $rowHoja['idUnico'];
        $datos['nomEquipo'] = $rowHoja['nomEquipo'];
        $idHoja = $datos['idHoja'];
        $idUnico = $datos['idUnico'];
        $nomEquipo = $datos['nomEquipo'];
    }

    $_SESSION['idHoja'] = $idHoja;
    $_SESSION['idUnico'] = $idUnico;

    if($resHoja->num_rows){
        header('Location: ../'. $ruta .'/consultar-equipo.php?existe=true&nomEquipo=' . $nomEquipo);
    }else{
        header('Location: ../'. $ruta .'/consultar-equipo.php?noExiste=true');
    }


}