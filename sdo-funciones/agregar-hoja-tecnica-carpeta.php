<?php

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
} else if ($rolUsuario2 == "oficina tecnica"){
    $rutaUsuario = "sdo-oficinaTecnica";
} else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idCategoria = $_POST['idCategoria'];
    $idHojaTecnica = $_POST['idHojaTecnica'];

    if (isset($_POST['idHojaTecnica'])) {

        $sqlUpdate = "UPDATE ordenes_trabajos_general SET idCategoria = $idCategoria WHERE idOrden = $idHojaTecnica";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if ($resUpdate) {
            header('Location: ../'. $rutaUsuario .'/carpeta-hojas-tecnicas.php?exitoAgregarHoja=true');
        } else {
            header('Location: ../'. $rutaUsuario .'/carpeta-hojas-tecnicas.php?errorAgregarHoja=true');
        }
    } else {
        header('Location: ../'. $rutaUsuario .'/carpeta-hojas-tecnicas.php?noExisteHojaTecnica=true');
    }
} else {
    echo "no entre al post";
}
