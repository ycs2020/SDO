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
$idDepartamento = isset($_SESSION['idDepartamento']);

$rolUsuario2 = strtolower($rolUsuario);

$rutaUsuario = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $idDepartamento = trim($_SESSION['idDepartamento']);
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaUsuario = "sdo-jefestecnicos";
    $idDepartamento = $_SESSION['idDepartamento'];
} else if ($rolUsuario2 == "administrador") {
    $idDepartamento = 1;
    $tipoUsuario = 'administrador';
    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaUsuario = "sdo-directiva";
    $idDepartamento = 1;
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idEquipo = $_POST['idEquipo'];

    //Datos Obligatorios
    $inputIDUnico = $_POST['inputIDUnico'];
    $servicio = $_POST['servicio'];
    $nombreEquipo = $_POST['nombreEquipo'];
    $carpeta = $_POST['carpeta'];

    echo $inputIDUnico . "<br>";
    echo $servicio. "<br>";;
    echo $nombreEquipo. "<br>";;
    echo $carpeta. "<br>";

    if (empty($inputIDUnico) || empty($servicio) || empty($nombreEquipo) || empty($carpeta)) {
        echo 'vacios los obligatorios';
        exit;
    } else {

        $idMovil = $_POST['idMovil'];
        //INFORMACIÓN TÉCNICA
        $inputInventario = $_POST['inputInventario'];
        $IDLicitacion = $_POST['IDLicitacion'];
        $ordenCompra = $_POST['ordenCompra'];
        $inputMantenimiento = $_POST['inputMantenimiento'];
        $fechaRecepcion = $_POST['fechaRecepcion'];
        $fechaCompra = $_POST['fechaCompra'];
        $fechaMarcha = $_POST['fechaMarcha'];
        $clase = $_POST['clase'];
        $subClase = $_POST['subClase'];
        $complejidad = $_POST['complejidad'];
        $riesgo = $_POST['riesgo'];
        $costoMantencion = $_POST['costoMantencion'];

        //INFORMACIÓN BÁSICA
        $valorConIva = $_POST['valorConIva'];
        $marcaEquipo = $_POST['marcaEquipo'];
        $modeloEquipo = $_POST['modeloEquipo'];
        $fechaFabricación = $_POST['fechaFabricación'];
        $vidaUtil = $_POST['vidaUtil'];
        $versionSoftware = $_POST['versionSoftware'];
        $paisOrigen = $_POST['paisOrigen'];
        $terminoGarantia = $_POST['terminoGarantia'];
        $numSerie = $_POST['numSerie'];

        //UBICACIÓN
        $recinto = $_POST['recinto'];
        $pma = $_POST['pma'];
        $coordenadas = $_POST['coordenadas'];

        if($valorConIva !== ''){
        }else{
            $valorConIva = 0;
        }

        //INSERTAMOS EN LA TABLA CORRESPONDIENTE
        // $sqlInsert = "INSERT INTO `equipamiento_hbqp`(`id`, `identificadorUnico`, `numeroInventario`, `fechaRecepcion`, `nombreEquipo`, `marca`, `modelo`, `idServicio`, `recinto`, `pma`, `coordenadas`, `fechaCompra`, `valorConIva`, `idLicitacion`, `ordenCompra`, `fechaMarcha`, `clase`, `subclase`, `complejidadTec`, `nivelRiesgo`, `AhnoFrabricacion`, `vidaUtil`, `versionSoftware`, `paisOrigen`, `terminoGarantia`, `costoMantAnual`, `numeroSerie`, `carpeta`) VALUES (null,'$inputIDUnico','$inputInventario','$fechaRecepcion','$nombreEquipo','$marcaEquipo','$modeloEquipo','$servicio','$recinto','$pma','$coordenadas','$fechaCompra',$valorConIva,'$IDLicitacion','$ordenCompra','$fechaMarcha','$clase','$subClase','$complejidad','$riesgo','$fechaFabricación','$vidaUtil','$versionSoftware','$paisOrigen','$terminoGarantia','$costoMantencion','$numSerie','$carpeta')";
        // $resInsert = mysqli_query($conexion, $sqlInsert);

        $sqlUpdate = "UPDATE `equipamiento_hbqp` SET `identificadorUnico`= '$inputIDUnico',`numeroInventario`='$inputInventario',`fechaRecepcion`='$fechaRecepcion',`nombreEquipo`='$nombreEquipo',`marca`='$marcaEquipo',`modelo`='$modelo',`idServicio`='$servicio',`recinto`='$recinto',`pma`='$pma',`coordenadas`='$coordenadas',`fechaCompra`='$fechaCompra',`valorConIva`=$valorConIva,`idLicitacion`='$IDLicitacion',`ordenCompra`='$ordenCompra',`fechaMarcha`='$fechaMarcha',`clase`='$clase',`subclase`='$subClase',`complejidadTec`='$complejidad',`nivelRiesgo`='$riesgo',`AhnoFrabricacion`='$fechaFabricación',`vidaUtil`='$vidaUtil',`versionSoftware`='$versionSoftware',`paisOrigen`='$paisOrigen',`terminoGarantia`='$terminoGarantia',`costoMantAnual`='$costoMantencion',`numeroSerie`='$numSerie',`carpeta`='$carpeta' WHERE id = $idEquipo ";
        echo $sqlUpdate;
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if($resUpdate){
            header('Location: ../'. $rutaUsuario.'/visualizarEquipo-cc.php?exitoEditar=true&&id=' .  $idEquipo);
        }else{
            header('Location: ../'. $rutaUsuario.'/visualizarEquipo-cc.php?errorEditar=true&&id=' .  $idEquipo);
        }


    }
}
