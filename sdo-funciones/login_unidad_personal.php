<?php
include("conexion.php");
include("funciones.php");

if (isset($_POST['usuarioRegistrado']) && isset($_POST['passwordRegistrado'])) {
    $usuarioRegistrado = trim($_POST['usuarioRegistrado']);
    $passwordRegistrado = trim($_POST['passwordRegistrado']);
    if (empty($usuarioRegistrado) || empty($passwordRegistrado) || $usuarioRegistrado == '' || $passwordRegistrado == '' || $usuarioRegistrado == ' ' || $passwordRegistrado == ' ') {
        header('Location: ../sdo-flujo/acceso_unidad_personal.php?errorLogin=true');
    }else{
        $sql = "SELECT * FROM unidad_personal WHERE userUnidadPersonal = '" . $usuarioRegistrado . "' AND passUnidadPersonal = '" . $passwordRegistrado . "'";
        $res = mysqli_query($conexion,$sql);
        $row = $res->fetch_assoc();
        if($res){
            if (session_status() == PHP_SESSION_NONE) {
                ini_set('session.cache_expire', 200000);
                ini_set('session.cache_limiter', 'none');
                ini_set('session.cookie_lifetime', 2000000);
                ini_set('session.gc_maxlifetime', 200000);
                session_start();
                ini_set('session.cache_expire', 200000);
                ini_set('session.cache_limiter', 'none');
                ini_set('session.cookie_lifetime', 2000000);
                ini_set('session.gc_maxlifetime', 200000);
            }
            $_SESSION['userUsuario'] = $row['userUnidadPersonal'];
            $_SESSION['nomUsuario'] = $row['nomUnidadPersonal'];
            $_SESSION['idUsuario'] = $row['idUnidadPersonal'];
            $_SESSION['login'] = true;
            $_SESSION['cargoUsuario'] = $row['rolUnidadPersonal'];
            $_SESSION['emailUsuario'] = $row['emailUnidadPersonal'];
            $_SESSION['rolUsuario'] = 'Funcionario Personal';
            $_SESSION['idUsuarioParaSubida'] = $row['idUnidadPersonal'];

            if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {
                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
            }else{
                if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                    header('Location: ../sdo-usuariosPersonal/index.php?loginExito=true');
                } else {
                    header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                }
            }

        }
    }
}