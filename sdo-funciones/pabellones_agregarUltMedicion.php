<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

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
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['nomRegistroPab'], $_POST['ordenRegistroPab'])) {

    $nomRegistroPab = $_POST['nomRegistroPab'];
    $ordenRegistroPab = $_POST['ordenRegistroPab'];
    $fechaRegistroPab = date("Y-m-d H:i:s");
    $valor = '';
    $terminadas = $_POST['terminadas'];

    if($terminadas == '' || empty($terminadas)){
        $terminadas = 0;
    }

    for ($i = 2; $i <= 10; $i++) {

        if (isset($_POST['addTempPabellon' . $i])) {
            if ($_POST['addTempPabellon' . $i] > -1) {
                if (isset($_POST['addHumedadPabellon' . $i])) {
                    if ($_POST['addHumedadPabellon' . $i] > -1) {
                        $valor = 'correcto';
                    } else {
                        if($terminadas > 0 || !empty($terminadas)){
                            header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?noData=true');
                        }else{
                            header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noData=true');
                        }
                    }
                } else {
                    if($terminadas > 0 || !empty($terminadas)){
                        header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?noData=true');
                    }else{
                        header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noData=true');
                    }
                }
            } else {
                if($terminadas > 0 || !empty($terminadas)){
                    header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?noData=true');
                }else{
                    header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noData=true');
                }
            }
        } else {
            if($terminadas > 0 || !empty($terminadas)){
                header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?noData=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noData=true');
            }
        }

    }

    if ($valor == 'correcto') {
        // Inicializar arrays para las temperaturas y humedades
        $tempPabellon = array();
        $humedadPabellon = array();

// Recorrer el bucle para obtener los valores de temperatura y humedad
        for ($k = 2; $k < 11; $k++) {
            $tempPabellon[$k] = $_POST['addTempPabellon' . $k];
            $humedadPabellon[$k] = $_POST['addHumedadPabellon' . $k];
        }

// Consulta SQL para insertar datos en la tabla `pabellones_correo`
        $sqlInsert = "INSERT INTO pabellones_correo (
    idRegistroPab, nomRegistroPab, fechaRegistroPab, ordenRegistroPab,
    " . implode(", ", array_map(function ($k) {
                return "tempPabellon$k, humedadPabellon$k";
            }, range(2, 10))) . "
) VALUES (
    NULL, '$nomRegistroPab', '$fechaRegistroPab', '$ordenRegistroPab',
    " . implode(", ", array_map(function ($k) use ($tempPabellon, $humedadPabellon) {
                return "'" . $tempPabellon[$k] . "', '" . $humedadPabellon[$k] . "'";
            }, range(2, 10))) . "
)";

//        echo $sqlInsert;
        if (mysqli_query($conexion, $sqlInsert)) {
            if($terminadas > 0 || !empty($terminadas)) {
                header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?exitoRegistrarParam=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?exitoRegistrarParam=true');

            }
        } else {
            if($terminadas > 0 || !empty($terminadas)) {
                header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?errorRegistrarParam=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorRegistrarParam=true');
            }
        }
    } else {
        if($terminadas > 0 || !empty($terminadas)){
            header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?noData=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noData=true');
        }    }

} else {
    if($terminadas > 0 || !empty($terminadas)){
        header('Location: ../' . $rutaDireccion . '/visualizardetallesterminadas.php?noData=true');
    }else{
        header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noData=true');
    }
}
/*
mysqli_close($conexion);
*/