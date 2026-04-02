<?php
include_once '../sdo-funciones/conexion.php';

        $sql = "SELECT * FROM `material_orden_general` ORDER BY `material_orden_general`.`idMaterial` DESC";
        $res = mysqli_query($conexion,$sql);
        while($row = $res->fetch_assoc()){
            $nomMaterial = $row['nomMaterial'];
            $cantidadMaterial = $row['cantidadMaterial'];
            $idOrden = $row['idOrden'];

            $sqlInventario = "SELECT * FROM `inventario_equipos` WHERE equipoInventario like '%$nomMaterial%'";
            $resInventario = mysqli_query($conexion,$sqlInventario);
            while($rowInventario = $resInventario->fetch_assoc()){
                $tipoDocumento = $rowInventario['tipoDocumento'];
            }
        }
        ?>