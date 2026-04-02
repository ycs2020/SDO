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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos de las filas del formulario
    $dataRows = json_decode($_POST['dataRows'], true);

    // Mostrar los datos por columna
//    echo "<table border='1'>";
//    foreach ($dataRows as $row) {
//        echo "<tr>";
//        foreach ($row as $column) {
//            echo "<td>$column</td>";
//        }
//        echo "</tr>";
//    }
//    echo "</table>";

    $cantidadDataRows = count($dataRows);

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m-d H:i:s', time());
    $anio = date('Y', time());

    if (isset($_POST['unidadCrea'])) {
        $unidadCrea = $_POST['unidadCrea'];

        $unidadCrea = trim($unidadCrea);

        if (empty($unidadCrea)) {
            $unidadCrea = 'Control Centralizado';
        }
    } else {
        $unidadCrea = 'Control Centralizado';
    }

    if ($cantidadDataRows > 0) {
        $descripcion = $_POST['descripcion'];

        $sqlPresup = "INSERT INTO presupuesto_equipos(idPresup, folioPresup, creadorPresup, fechaPresup, unidadPresup, valorPresup,descripcion) 
        VALUES (NULL,'','" . $nomUsuario . "','" . $now . "','" . $unidadCrea . "','0','$descripcion')";

//        echo $sqlPresup;

        $resPresup = mysqli_query($conexion, $sqlPresup);

        if ($resPresup) {

            $idPresupuesto = mysqli_insert_id($conexion);

            $valorTotalFinal = 0;

            if ($idPresupuesto > 0) {

                foreach ($dataRows as $row) {
                    $contar = 1;
                    foreach ($row as $column) {
                        if($contar == 1){
                            $idInventario = ltrim($column, "#");
                        }else if($contar == 2){
                            $nomEquipo = $column;
                        }else if($contar == 3){
                            $unidad = $column;
                        }else if($contar == 4){
                            $marca = $column;
                        }else if($contar == 5){
                            $modelo = $column;
                        }else if($contar == 6){
                            $cantidad = $column;
                        }else if($contar == 7){
                            $valorUnitario = $column;

                            $valorMonetarioSinSimbolo = str_replace(array('$', '.'), '', $valorUnitario);
                            $valorMonetarioSinComas = str_replace(',', '.', $valorMonetarioSinSimbolo);

                            $valorDecimal = floatval($valorMonetarioSinComas);
                            $valorUnitario = intval($valorDecimal);

                        }else if($contar == 8){
                            $valorTotal = $column;

                            $valorMonetarioSinSimbolo = str_replace(array('$', '.'), '', $valorTotal);
                            $valorMonetarioSinComas = str_replace(',', '.', $valorMonetarioSinSimbolo);

                            $valorDecimal = floatval($valorMonetarioSinComas);
                            $valorTotal = intval($valorDecimal);
                        }else{
                            $valorNo = 'No Aplica';
                        }

                        $contar++;
                    }
                    $sqlEquipo = "INSERT INTO equipos_presupuesto_equipos(idEquipo, nomEquipo, unidadEquipo, marcaEquipo, modeloEquipo, cantidadEquipo, valorUnitarioEquipo, valorTotalEquipo, idInventarioEquipo, idPresup)  VALUES (NULL,'".$nomEquipo."','".$unidad."','".$marca."','".$modelo."',".$cantidad.",'".$valorUnitario."','".$valorTotal."','".$idInventario."','".$idPresupuesto."')";


                    $resEquipo = mysqli_query($conexion, $sqlEquipo);

                    if ($resEquipo) {
                        $valorTotalFinal = $valorTotalFinal + $valorTotal;
                    }else{
                        $valorTotalFinal = $valorTotalFinal + 0;
                    }


                }

                if($idPresupuesto > 0 && $idPresupuesto < 10){

                    $folioNuevo = $anio.'-P000'.$idPresupuesto;
                }else if($idPresupuesto > 9 && $idPresupuesto < 100){

                    $folioNuevo = $anio.'-P00'.$idPresupuesto;
                }else if($idPresupuesto > 99 && $idPresupuesto < 1000){

                    $folioNuevo = $anio.'-P0'.$idPresupuesto;
                }else{

                    $folioNuevo = $anio.'-P'.$idPresupuesto;
                }


                $sqlUpdate = "UPDATE presupuesto_equipos SET valorPresup = ".$valorTotalFinal." , folioPresup = '".$folioNuevo."' WHERE idPresup = ".$idPresupuesto;

                $resUpdate = mysqli_query($conexion, $sqlUpdate);

                if($resUpdate){
                    header('Location: ../' . $rutaDireccion . '/presupuesto-equipo.php?exitoAgregarPresupuesto=true');
                }else{
                    header('Location: ../' . $rutaDireccion . '/presupuesto-equipo.php?errorAgregarPresupuesto=true');
                }


            } else {
                header('Location: ../' . $rutaDireccion . '/presupuesto-equipo.php?errorAgregarPresupuesto=true');

            }

        } else {
            header('Location: ../' . $rutaDireccion . '/presupuesto-equipo.php?errorAgregarPresupuesto=true');
        }
    } else {
        header('Location: ../' . $rutaDireccion . '/presupuesto-equipo.php?errorAgregarPresupuesto=true');
    }
} else {
    header('Location: ../' . $rutaDireccion . '/presupuesto-equipo.php?errorAgregarPresupuesto=true');
}