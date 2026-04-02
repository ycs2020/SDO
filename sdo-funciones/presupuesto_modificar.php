<?php
include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


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
    $sqls_ejecutadas = array();
    $dataRows = json_decode($_POST['dataRows'], true);
    $cantidadDataRows = count($dataRows);
    date_default_timezone_set('America/Santiago');
    $now = date('Y-m-d H:i:s', time());
    $anio = date('Y', time());
    $idPresupuesto = $_POST['idPresup1'];
    $descripcion = $_POST['descripcion'];
    $valorTotalFinal = 0;

    $sqlUpdate = "UPDATE presupuesto_equipos SET descripcion = '$descripcion' WHERE idPresup = $idPresupuesto";
    $resUpdate = mysqli_query($conexion,$sqlUpdate);

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

        $buscarequipopresu = buscarequipopresu($idInventario,$idPresupuesto);
        if($buscarequipopresu > 0){
            $sql = "UPDATE `equipos_presupuesto_equipos` SET `cantidadEquipo`= $cantidad,`valorUnitarioEquipo`= $valorUnitario,`valorTotalEquipo`= $valorTotal WHERE idInventarioEquipo = $idInventario AND idPresup = $idPresupuesto";
        }else{
            $sql = "INSERT INTO equipos_presupuesto_equipos(idEquipo, nomEquipo, unidadEquipo, marcaEquipo, modeloEquipo, cantidadEquipo, valorUnitarioEquipo, valorTotalEquipo, idInventarioEquipo, idPresup)  
            VALUES (NULL,'".$nomEquipo."','".$unidad."','".$marca."','".$modelo."',".$cantidad.",'".$valorUnitario."','".$valorTotal."','".$idInventario."','".$idPresupuesto."')";
        }
        $sqls_ejecutadas[] = $sql;

        $valorTotalFinal = $valorTotalFinal + $valorTotal;

    }

    // Ejecutar todas las consultas fuera del bucle foreach
    foreach ($sqls_ejecutadas as $sql) {
        $res = mysqli_query($conexion,$sql);
    }

    $sqlUpdate = "UPDATE presupuesto_equipos SET valorPresup = ".$valorTotalFinal." WHERE idPresup = ".$idPresupuesto;

    $resUpdate = mysqli_query($conexion, $sqlUpdate);

    if($resUpdate){
        header('Location: ../' . $rutaDireccion . '/modificar-presupuesto.php?exitoModificarEquiposPresu=true');
    }else{
        header('Location: ../' . $rutaDireccion . '/modificar-presupuesto.php?errorModificarEquiposPresu=true');
    }


}
