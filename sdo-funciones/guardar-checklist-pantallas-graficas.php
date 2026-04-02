<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

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

$fecha = date('Y-m-d H:i:s');

if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $pantalla = $_POST['pantalla'];
    $especialidad = $_POST['especialidad'];
    $enlaceBotones = $_POST['enlaceBotones'];
    $enlaceImagenes = $_POST['enlaceImagenes'];
    $enlaceparametros = $_POST['enlaceparametros'];
    $enlacegraficas = $_POST['enlacegraficas'];
    $enlacetendencias = $_POST['enlacetendencias'];
    $enlacetextos = $_POST['enlacetextos'];
    $enlacestatus = $_POST['enlacestatus'];
    $enlacehumedad = $_POST['enlacehumedad'];
    $obs = $_POST['obs'];
    $tipoCheckList = $_POST['tipoCheckList'];

    if($enlaceBotones == '' || empty($enlaceBotones)){
        $enlaceBotones = 0;
    }
    if($enlaceImagenes == '' || empty($enlaceImagenes)){
        $enlaceImagenes = 0;
    }
    if($enlaceparametros == '' || empty($enlaceparametros)){
        $enlaceparametros = 0;
    }
    if($enlacegraficas == '' || empty($enlacegraficas)){
        $enlacegraficas = 0;
    }
    if($enlacetendencias == '' || empty($enlacetendencias)){
        $enlacetendencias = 0;
    }
    if($enlacetextos == '' || empty($enlacetextos)){
        $enlacetextos = 0;
    }
    if($enlacestatus == '' || empty($enlacestatus)){
        $enlacestatus = 0;
    }
    if($enlacehumedad == '' || empty($enlacehumedad)){
        $enlacehumedad = 0;
    }



    $sql = "INSERT INTO `datos_checklist`(`id`,`fecha`,folio,`pantalla`, `especialidad`, `obs`, `enlaceBotones`, `enlaceImagenes`, `enlaceParametros`, `enlaceGraficas`, `enlaceTendencias`, `enlaceTextos`, `revisionStatus`, `temperaturaHumedad`,tipo_check_list,departamento, `idTG`) 
    VALUES (NULL,'$fecha','0','$pantalla','$especialidad','$obs',$enlaceBotones,$enlaceImagenes,$enlaceparametros,$enlacegraficas,$enlacetendencias,$enlacetextos,$enlacestatus,$enlacehumedad,'$tipoCheckList',$idDepartamento,$idTG)";
    $res = mysqli_query($conexion,$sql);
    if($res){
        $id = mysqli_insert_id($conexion);

        if ($id < 10) {
            $idRecepcion2 = "000" . $id;
        } else if ($idTG > 9 && $id < 100) {
            $idRecepcion2 = "00" . $id;
        } else if ($idTG > 99 && $id < 1000) {
            $idRecepcion2 = "0" . $id;
        } else if ($idTG > 999) {
            $idRecepcion2 = $id;
        } else {
            $idRecepcion2 = $id;
        }

        $folio = 'CHK-'.$idRecepcion2;

        $sqlUpdate = "UPDATE datos_checklist SET folio = '$folio' WHERE id = $id";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);

        if($resUpdate){
            if($tipoUsuario != 'personal'){
                header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoPantallasGraficas=true');
            }else{
                header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoPantallasGraficas=true');
            }
        }

    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorPantallasGraficas=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorPantallasGraficas=true');
        }
    }

}
