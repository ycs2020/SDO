<?php

include("conexion.php");
include("funciones.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['passwordRestaurar'], $_POST['codigoRegistrado'], $_POST['usuarioRegistrado'])) {

    $passwordRestaurar = trim($_POST['passwordRestaurar']);
    $codigoRegistrado = $_POST['codigoRegistrado'];
    $usuarioRegistrado = $_POST['usuarioRegistrado'];

    if (!empty($passwordRestaurar) && !empty($codigoRegistrado) && !empty($usuarioRegistrado)) {

        if (strlen($passwordRestaurar) > 7) {

            if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRestaurar)) {
                $dpass = saveEncPass($passwordRestaurar);

                $idPassReco = existePR($usuarioRegistrado, $codigoRegistrado);

                // echo 'hola '.$idPassReco2;

                if ($idPassReco > 0) {

                    $tipoUserPassReco = buscarTipoUserPassReco($idPassReco);

                    $BDBuscar = '';
                    $valorPassword = '';
                    $valorUsuario = '';

                    if ($tipoUserPassReco == "Tecnico") {

                        $BDBuscar = "personal";
                        $valorPassword = "passPersonal";
                        $valorUsuario = "userPersonal";
                    } else if ($tipoUserPassReco == "Profesional") {

                        $BDBuscar = "jefes_tecnicos";
                        $valorPassword = "passJefe";
                        $valorUsuario = "userJefe";
                    } else if ($tipoUserPassReco == "Administrador") {

                        $BDBuscar = "administracion";
                        $valorPassword = "passAdministrador";
                        $valorUsuario = "userAdministrador";
                    } else if ($tipoUserPassReco == "Administrativo") {

                        $BDBuscar = "adm_tecnicos";
                        $valorPassword = "passAdmTecnicos";
                        $valorUsuario = "userAdmTecnicos";
                    } else if ($tipoUserPassReco == "Directivo") {

                        $BDBuscar = "directivos";
                        $valorPassword = "passDirectivo";
                        $valorUsuario = "userDirectivo";
                    } else if ($tipoUserPassReco == "Mesa Ayuda") {

                        $BDBuscar = "mesa_ayuda";
                        $valorPassword = "passMesaAyuda";
                        $valorUsuario = "userMesaAyuda";
                    } else if ($tipoUserPassReco == "Gestión Documental") {

                        $BDBuscar = "gestion_documental";
                        $valorPassword = 'passGestionDoc';
                        $valorUsuario = 'userGestionDoc';
                    } else {

                        header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
                    }

                    $queryCambiarClave = "UPDATE $BDBuscar SET $valorPassword = '$passwordRestaurar' WHERE $valorUsuario = '$usuarioRegistrado'";

                    $resCambiarClave = mysqli_query($conexion, $queryCambiarClave);

                    if ($resCambiarClave) {
                        header('Location: ../sdo-flujo/login_index.php?exitoRestauracionPassword=true');
                    } else {
                        header('Location: ../sdo-flujo/login_index.php?errorRestauracionPassword=true');
                    }
                } else {
                    header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
                }
            } else {
                header('Location: ../sdo-flujo/login_index.php?errorCambioPasswordCaracteres=true');
            }
        } else {
            header('Location: ../sdo-flujo/login_index.php?errorCambioLargo=true');
        }
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorCambioPassword=true');
}
