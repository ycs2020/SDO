
<?php

    include  'conexion.php';

    if($_POST['id']){

        $id = $_POST['id'];

        //OBTENEMOS LA RUTA DEL ARCHIVO
        $sql = "SELECT * FROM archivos_equipamiento_hbqp WHERE id = $id";
        $res = mysqli_query($conexion, $sql);
        while($row = $res->fetch_assoc()){
            $datos = [];
            $datos['rutaArchivo'] = $row['rutaArchivo'];
            $rutaArchivo = $datos['rutaArchivo'];
        }

        if(file_exists($rutaArchivo)){
            if(unlink($rutaArchivo)){
//                echo 'Archivo eliminado';
                $sql = "DELETE FROM archivos_equipamiento_hbqp WHERE id = $id";
                $res = mysqli_query($conexion, $sql);
            }
        }else{
//            echo 'el archivo no existe';
            $sql = "DELETE FROM archivos_equipamiento_hbqp WHERE id = $id";
            $res = mysqli_query($conexion, $sql);
        }
    }