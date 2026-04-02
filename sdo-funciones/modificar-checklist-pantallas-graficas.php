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


if(isset($_POST['id'])){
    $id = $_POST['id'];
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

    $sql = "UPDATE datos_checklist SET pantalla = '$pantalla',especialidad = '$especialidad', obs = '$obs', enlaceBotones = $enlaceBotones, enlaceImagenes = $enlaceImagenes,enlaceParametros = $enlaceparametros, enlaceGraficas = $enlacegraficas, enlaceTendencias = $enlacetendencias, enlaceTextos = $enlacetextos, revisionStatus = $enlacestatus, temperaturaHumedad = $enlacehumedad WHERE id = $id ";
    $res = mysqli_query($conexion, $sql);

    if($res){
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?exitoModificarPantallasGraficas=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?exitoModificarPantallasGraficas=true');
        }
    }else{
        if($tipoUsuario != 'personal'){
            header('Location: ../'.$rutaDireccion . '/detalle_ordenes_trabajo.php?errorModificarPantallasGraficas=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetalles.php?errorModificarPantallasGraficas=true');
        }
    }
    //$tipoCheckList = $_POST['tipoCheckList'];

}