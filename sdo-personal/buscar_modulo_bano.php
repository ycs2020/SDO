<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';


$idTirador = $_POST['id_tirador'];

// Obtener recinto y pma del tirador
$sqlTirador = "SELECT recinto, pma 
               FROM equipamiento_hbqp 
               WHERE id = '$idTirador'";
$resTirador = mysqli_query($conexion, $sqlTirador);
$data = mysqli_fetch_assoc($resTirador);

// Buscar el módulo de baño correspondiente
$sqlModulo = "SELECT id 
              FROM equipamiento_hbqp 
              WHERE nombreEquipo = 'Módulo de baño (NX0062)'
              AND recinto = '{$data['recinto']}'
              AND pma = '{$data['pma']}'";

$resModulo = mysqli_query($conexion, $sqlModulo);
$modulo = mysqli_fetch_assoc($resModulo);

// Devuelve SOLO el ID
echo $modulo['id'];
