<?php
// MODIFICAR PARA LICITACION

include("conexion.php");
include("funciones.php");


if (isset($_POST['usuarioRegistrado']) && isset($_POST['passwordRegistrado'])) {
  $usuarioRegistrado = trim($_POST['usuarioRegistrado']);
  $passwordRegistrado = trim($_POST['passwordRegistrado']);

    if (empty($usuarioRegistrado) || empty($passwordRegistrado) || $usuarioRegistrado == '' || $passwordRegistrado == '' || $usuarioRegistrado == ' ' || $passwordRegistrado == ' ') {
        header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
    } else {

        $sqlAdmin = "SELECT * FROM administracion WHERE userAdministrador = '" . $usuarioRegistrado . "' AND passAdministrador = '" . $passwordRegistrado . "'";
        $resAdmin = mysqli_query($conexion, $sqlAdmin);
        $rowAdmin = $resAdmin->fetch_assoc();
        if ($rowAdmin) {

            echo "hh";

            // iniciar la sesion
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
            $_SESSION['userUsuario'] = $rowAdmin['userAdministrador'];
            $_SESSION['nomUsuario'] = $rowAdmin['nomAdministrador'];
            $_SESSION['idUsuario'] = $rowAdmin['idAdministrador'];
            $_SESSION['login'] = true;
            $_SESSION['cargoUsuario'] = $rowAdmin['rolAdministrador'];
            $_SESSION['emailUsuario'] = $rowAdmin['emailAdministrador'];
            $_SESSION['rolUsuario'] = 'Administrador';
            $_SESSION['idUsuarioParaSubida'] = $rowAdmin['idAdministrador'];

            if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
            }else{
                if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                    header('Location: ../sdo-administrador/index.php?loginExito=true');
                } else {
                    header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                }
            }
        } else {

            $sqlPersonal = "SELECT * FROM personal WHERE userPersonal = '" . $usuarioRegistrado . "' AND passPersonal = '" . $passwordRegistrado  . "'";
            $resPersonal = mysqli_query($conexion, $sqlPersonal);
            $rowPersonal = $resPersonal->fetch_assoc();
            if ($rowPersonal) {

                $activoPersonal = $rowPersonal['activoPersonal'];

                if ($activoPersonal == 1) {
                    // iniciar la sesion
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
                    $_SESSION['userUsuario'] = $rowPersonal['userPersonal'];
                    $_SESSION['nomUsuario'] = $rowPersonal['nomPersonal'];
                    $_SESSION['idUsuario'] = $rowPersonal['idPersonal'];
                    $_SESSION['cargoUsuario'] = $rowPersonal['cargoPersonal'];
                    $_SESSION['login'] = true;
                    $_SESSION['rolUsuario'] = 'Personal';
                    $_SESSION['idDepartamento'] = $rowPersonal['departamentoPersonal'];
                    $_SESSION['idSubDepartamento'] = $rowPersonal['idSubdepartamento'];
                    $_SESSION['departamentoPersonal'] = getNomDepartamento($rowPersonal['departamentoPersonal']);
                    $_SESSION['idUsuarioParaSubida'] = $rowPersonal['idPersonal'];

                    if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                        header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                    }else{
                        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                            header('Location: ../sdo-personal/index.php?loginExito=true');
                        } else {
                            header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                        }
                    }
                } else {
                    header('Location: ../sdo-flujo/login_index.php?faltaActivacion=true');
                }
            } else {

                $sqlDirectiva = "SELECT * FROM directivos WHERE userDirectivo = '" . $usuarioRegistrado . "' AND passDirectivo = '" . $passwordRegistrado . "'";
                $resDirectiva = mysqli_query($conexion, $sqlDirectiva);
                $rowDirectiva = $resDirectiva->fetch_assoc();
                if ($rowDirectiva) {
                    // iniciar la sesion
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
                    $_SESSION['userUsuario'] = $rowDirectiva['userDirectivo'];
                    $_SESSION['nomUsuario'] = $rowDirectiva['nomDirectivo'];
                    $_SESSION['idUsuario'] = $rowDirectiva['idDirectivo'];
                    $_SESSION['correoUsuario'] = $rowDirectiva['emailDirectivo'];
                    $_SESSION['login'] = true;
                    $_SESSION['rolUsuario'] = 'Directiva';
                    $_SESSION['cargoUsuario'] = $rowDirectiva['cargoDirectivo'];
                    $_SESSION['idUsuarioParaSubida'] = $rowDirectiva['idDirectivo'];

                    if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                        header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                    }else{
                        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                            header('Location: ../sdo-directiva/index.php?loginExito=true');
                        } else {
                            header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                        }
                    }
                } else {

                    $sqlJefeTecnico = "SELECT * FROM jefes_tecnicos WHERE userJefe = '" . $usuarioRegistrado . "' AND passJefe = '" . $passwordRegistrado . "' AND userActivado = 1";
                    $resJefeTecnico = mysqli_query($conexion, $sqlJefeTecnico);
                    $rowJefeTecnico = $resJefeTecnico->fetch_assoc();
                    if ($rowJefeTecnico) {
                        // iniciar la sesion
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
                        $_SESSION['userUsuario'] = $rowJefeTecnico['userJefe'];
                        $_SESSION['nomUsuario'] = $rowJefeTecnico['nomJefe'];
                        $_SESSION['idUsuario'] = $rowJefeTecnico['idJefe'];
                        $_SESSION['idDepartamento'] = $rowJefeTecnico['idDepartamento'];
                        $_SESSION['cargoJefe'] = $rowJefeTecnico['cargoJefe'];
                        $_SESSION['idSubDepartamento'] = $rowJefeTecnico['idSubdepartamento'];
                        $_SESSION['emailJefe'] = $rowJefeTecnico['emailJefe'];
                        $_SESSION['login'] = true;
                        $_SESSION['rolUsuario'] = 'Jefe Tecnico';
                        $_SESSION['idUsuarioParaSubida'] = $rowJefeTecnico['idJefe'];

                        if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                            header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                        }else{
                            if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                                header('Location: ../sdo-jefestecnicos/index.php?loginExito=true');
                            } else {
                                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                            }
                        }
                    } else {

                        $sqlAdmTec = "SELECT * FROM adm_tecnicos WHERE userAdmTecnicos = '" . $usuarioRegistrado . "' AND passAdmTecnicos = '" . $passwordRegistrado . "'";
                        $resAdmTec = mysqli_query($conexion, $sqlAdmTec);
                        $rowAdmTec = $resAdmTec->fetch_assoc();
                        if ($rowAdmTec) {
                            // iniciar la sesion
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
                            $_SESSION['userUsuario'] = $rowAdmTec['userAdmTecnicos'];
                            $_SESSION['nomUsuario'] = $rowAdmTec['nomAdmTecnicos'];
                            $_SESSION['idUsuario'] = $rowAdmTec['idAdmTecnicos'];
                            $_SESSION['idDepartamento'] = $rowAdmTec['idDepartamento'];
                            $_SESSION['nomDepartamento'] =  getNomDepartamento($rowAdmTec['idDepartamento']);
                            $_SESSION['login'] = true;
                            $_SESSION['rolUsuario'] = 'Administrativo';
                            $_SESSION['idUsuarioParaSubida'] = $rowJefeTecnico['idAdmTecnicos'];

                            if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                            }else{
                                if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                                    header('Location: ../sdo-admtec/index.php?loginExito=true');
                                } else {
                                    header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                }
                            }

                            // echo $_SESSION['idSubDepartamento'] . '<br><br>';
                            // echo $_SESSION['userUsuario'] . '<br><br>';
                            // echo $_SESSION['nomUsuario'] . '<br><br>';
                            // echo $_SESSION['idUsuario'] . '<br><br>';
                            // echo $_SESSION['login'] . '<br><br>';
                            // echo $_SESSION['rolUsuario'] . '<br><br>';
                            // echo $_SESSION['idUsuarioParaSubida'] . '<br><br>';
                        } else {

                            $sqlMesaAyuda = "SELECT * FROM mesa_ayuda WHERE userMesaAyuda = '" . $usuarioRegistrado . "' AND passMesaAyuda = '" . $passwordRegistrado . "'";
                            $resMesaAyuda = mysqli_query($conexion, $sqlMesaAyuda);
                            $rowMesaAyuda = $resMesaAyuda->fetch_assoc();
                            if ($rowMesaAyuda) {
                                // iniciar la sesion
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
                                $_SESSION['userUsuario'] = $rowMesaAyuda['userMesaAyuda'];
                                $_SESSION['nomUsuario'] = $rowMesaAyuda['nomMesaAyuda'];
                                $_SESSION['idUsuario'] = $rowMesaAyuda['idMesaAyuda'];
                                $_SESSION['emailUsuario'] = $rowMesaAyuda['emailMesaAyuda'];
                                $_SESSION['login'] = true;
                                $_SESSION['rolUsuario'] = 'Mesa Ayuda';
                                $_SESSION['idUsuarioParaSubida'] = $rowJefeTecnico['idMesaAyuda'];

                                if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                                    header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                }else{

                                    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                                        header('Location: ../sdo-mesa-ayuda/index.php?loginExito=true');
                                    } else {
                                        header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                    }
                                }

                                // echo $_SESSION['idSubDepartamento'] . '<br><br>';
                                // echo $_SESSION['userUsuario'] . '<br><br>';
                                // echo $_SESSION['nomUsuario'] . '<br><br>';
                                // echo $_SESSION['idUsuario'] . '<br><br>';
                                // echo $_SESSION['login'] . '<br><br>';
                                // echo $_SESSION['rolUsuario'] . '<br><br>';
                                // echo $_SESSION['idUsuarioParaSubida'] . '<br><br>';
                            } else {

                                $sqlMesaAyuda = "SELECT * FROM bodega_pañol WHERE userBodegaPañol = '" . $usuarioRegistrado . "' AND passBodegaPañol = '" . $passwordRegistrado . "'";
                                $resBodegaPañol = mysqli_query($conexion, $sqlMesaAyuda);
                                $rowBodegaPañol = $resBodegaPañol->fetch_assoc();
                                if ($rowBodegaPañol) {
                                    // iniciar la sesion
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
                                    $_SESSION['userUsuario'] = $rowBodegaPañol['userBodegaPañol'];
                                    $_SESSION['nomUsuario'] = $rowBodegaPañol['nomBodegaPañol'];
                                    $_SESSION['idUsuario'] = $rowBodegaPañol['idBodegaPañol'];
                                    $_SESSION['login'] = true;
                                    $_SESSION['rolUsuario'] = 'Bodega Pañol';
                                    $_SESSION['idUsuarioParaSubida'] = $rowBodegaPañol['idBodegaPañol'];

                                    if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                                        header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                    }else{
                                        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                                            header('Location: ../sdo-bodega-panol/index.php?loginExito=true');
                                        } else {
                                            header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                        }
                                    }
                                } else {

                                    $sqlGestDoc = "SELECT * FROM gestion_documental WHERE userGestionDoc = '" . $usuarioRegistrado . "' AND passGestionDoc = '" . $passwordRegistrado . "'";
                                    $resGestDoc = mysqli_query($conexion, $sqlGestDoc);
                                    $rowGestDoc = $resGestDoc->fetch_assoc();
                                    if ($rowGestDoc) {
                                        // iniciar la sesion
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
                                        $_SESSION['userUsuario'] = $rowGestDoc['userGestionDoc'];
                                        $_SESSION['nomUsuario'] = $rowGestDoc['nomGestionDoc'];
                                        $_SESSION['idUsuario'] = $rowGestDoc['idGestionDoc'];
                                        $_SESSION['cargoUsuario'] = $rowGestDoc['cargoGestionDoc'];
                                        $_SESSION['correoUsuario'] = $rowGestDoc['emailGestionDoc'];
                                        $_SESSION['login'] = true;
                                        $_SESSION['rolUsuario'] = 'Gestión Documental';
                                        $_SESSION['idUsuarioParaSubida'] = $rowGestDoc['idGestionDoc'];

                                        if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {

                                            header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                        }else{
                                            if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                                                header('Location: ../sdo-gestionDocumental/index.php?loginExito=true');
                                            } else {
                                                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                            }
                                        }
                                    } else {
                                        $sqlOfiTec = "SELECT * FROM oficina_tecnica WHERE userOfi = '" . $usuarioRegistrado . "' AND passOfi = '" . $passwordRegistrado . "'";
                                        $resOfiTec = mysqli_query($conexion, $sqlOfiTec);
                                        $rowOfiTec = $resOfiTec->fetch_assoc();

                                        if($rowOfiTec){
                                            // iniciar la sesion
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
                                            $_SESSION['userUsuario'] = $rowOfiTec['userOfi'];
                                            $_SESSION['nomUsuario'] = $rowOfiTec['nomOfi'];
                                            $_SESSION['idUsuario'] = $rowOfiTec['idOficina'];
                                            $_SESSION['cargoUsuario'] = $rowOfiTec['cargoOfi'];
                                            $_SESSION['correoUsuario'] = $rowOfiTec['emailOfi'];
                                            $_SESSION['login'] = true;
                                            $_SESSION['rolUsuario'] = 'Oficina Tecnica';
                                            $_SESSION['idUsuarioParaSubida'] = $rowOfiTec['idOficina'];

                                            if($passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025' || $passwordRegistrado == '@Sdo2025' || $passwordRegistrado == '@SDO2025' || $passwordRegistrado == '@sdo2025') {
                                                header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                            }else{
                                                if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $passwordRegistrado)) {
                                                    header('Location: ../sdo-oficinaTecnica/index.php?loginExito=true');
                                                } else {
                                                    header('Location: ../sdo-flujo/cambiarPassword.php?cambiarPassword=true');
                                                }
                                            }


                                        }else{
                                            header('Location: ../sdo-flujo/login_index.php?noExisteUsuario=true');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} else {
  header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
}
