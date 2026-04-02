<?php
include ("conexion.php");
include("funciones.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['passwordRestaurar'], $_POST['codigoRegistrado'], $_POST['rutRegistrado'])){
        $passwordRestaurar = trim($_POST['passwordRestaurar']);
        $codigoRegistrado = $_POST['codigoRegistrado'];
        $rutRegistrado = $_POST['rutRegistrado'];

        if(!empty($rutRegistrado) && !empty($codigoRegistrado) && !empty($passwordRestaurar)){
            if (strlen($passwordRestaurar) > 7) {
                if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRestaurar)) {
                    $dpass = saveEncPass($passwordRestaurar);
                    $idPassTicket = existePRticket($rutRegistrado, $codigoRegistrado);
                    if($idPassTicket > 0){
                        $sql = "UPDATE usuarios_tickets SET passUsuariosT = '$passwordRestaurar' WHERE rutUsuariosT = '$rutRegistrado'";
                        $res = mysqli_query($conexion,$sql);
                        if($res){
                            header('Location: ../sdo-flujo/soporte_index.php?exitoRestauracionPassword=true');
                        }else{
                            header('Location: ../sdo-flujo/soporte_index.php?errorRestauracionPassword=true');
                        }
                    }else{
                        header('Location: ../sdo-flujo/soporte_index.php?errorCambioPassword=true');
                    }

                }else{
                    header('Location: ../sdo-flujo/soporte_index.php?errorCambioPasswordCaracteres=true');
                }
            }
        }
}


?>