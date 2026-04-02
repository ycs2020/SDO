<?php


include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['id'])){

        $idEquipo = $_POST['id'];

        if($idEquipo > 0){

        $sql = "UPDATE equipamiento_hbqp  SET idEquipoPadre = NULL WHERE id = '$idEquipo'";
        $resSQL = mysqli_query($conexion, $sql);

            if ($resSQL && mysqli_affected_rows($conexion) > 0) {
                echo 'confirmo';
            }else{
                echo 'error';
            }

        }else{
            echo 'error';
        }

    }else{
        echo 'error';
    }
}else{
    echo 'error';
}