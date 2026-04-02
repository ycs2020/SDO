ñ<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['idEquipo']){
        $idEquipo = $_POST['idEquipo'];
        $idCategoria = $_POST['idCategoria'];

        //RECIBE LOS DATOS POR MÉTODO POST
        $opcionEstado = $_POST['opcionEstado'];
        $observaciones = $_POST['observaciones'];
        $nombreEquipo = $_POST['nombreEquipo'];

        //ACTUALIZAMOS EL CAMBIO EN LA TABLA HOJA DE VIDA
        $sqlUpdate = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $idEquipo";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);
        if($resUpdate){
            /*CÓDIGO COMPRUEBA SI YA EXISTE REGISTRO DEL ESTADO EN SU TABLA CORRESPONDIENTE*/
            $existeRegistroObservacion = compruebaRegistroObservacion($idEquipo);

            if($existeRegistroObservacion == "Si"){
                $sqlElimina = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $idEquipo";
                $resElimina = mysqli_query($conexion, $sqlElimina);
                if($resElimina ){
                }else{
                    if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                        header('Location: ../'.$ruta.'/equipo.php?error=1');
                    }else if($idDepartamento == 6){
                        header('Location: ../'.$ruta.'/equipo-inge-clinica.php?error=1');
                    }else if($rolUsuario2 == "personal"){
                        header('Location: ../'.$ruta.'/equipo.php?error=1');
                    }else{
                        header('Location: ../'.$ruta.'/carpeta-equipo.php?error=1');
                    }                }
            }

            $existeRegistroFalla = compruebaRegistroFalla($idEquipo);
            if($existeRegistroFalla == "Si"){
                $sqlElimina = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $idEquipo";
                $resElimina = mysqli_query($conexion, $sqlElimina);
                if($resElimina ){
                }else{
                    if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                        header('Location: ../'.$ruta.'/equipo.php?error=2');
                    }else if($idDepartamento == 6){
                        header('Location: ../'.$ruta.'/equipo-inge-clinica.php?error=2');
                    }else if($rolUsuario2 == "personal"){
                        header('Location: ../'.$ruta.'/equipo.php?error=2');
                    }else{
                        header('Location: ../'.$ruta.'/carpeta-equipo.php?error=2');
                    }
                }
            }

            /*if($opcionEstado == 'Mantenimiento'){
                $sqlInsert = "INSERT INTO historial_cambios_equipos(estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo)
                VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria,'$nombreEquipo')";
                $resInsert = mysqli_query($conexion, $sqlInsert);
            }*/
            if($opcionEstado == 'Fuera de Servicio'){
                $sqlInsert = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nomEquipo) 
                VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria,'$nombreEquipo')";
                $resInsert = mysqli_query($conexion, $sqlInsert);
            }

            //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
            $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
            VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria,'$nombreEquipo')";
            $resInsert = mysqli_query($conexion, $sqlInsert);

            $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
            VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria,'$nombreEquipo')";
            $resInsert = mysqli_query($conexion, $sqlInsert);


            if($resInsert){
                if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                    header('Location: ../'.$ruta.'/equipo.php?exitoCambioEstado=true');
                }else if($idDepartamento == 6){
                    header('Location: ../'.$ruta.'/equipo-inge-clinica.php?exitoCambioEstado=true');
                }else if($rolUsuario2 == "personal"){
                    header('Location: ../'.$ruta.'/equipo.php?exitoCambioEstado=true');
                }else{
                    header('Location: ../'.$ruta.'/carpeta-equipo.php?exitoCambioEstado=true');
                }
            }else{
                if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                    header('Location: ../'.$ruta.'/equipo.php?errorCambioEstado=true');
                }else if($idDepartamento == 6){
                    header('Location: ../'.$ruta.'/equipo-inge-clinica.php?errorCambioEstado=true');
                }else if($rolUsuario2 == "personal"){
                    header('Location: ../'.$ruta.'/equipo.php?errorCambioEstado=true');
                }else{
                    header('Location: ../'.$ruta.'/carpeta-equipo.php?errorCambioEstado=true');
                }
            }
        }else{
            if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico") {
                header('Location: ../'.$ruta.'/equipo.php?noCambio=true');
            }else if($idDepartamento == 6){
                header('Location: ../'.$ruta.'/equipo-inge-clinica.php?noCambio=true');
            }else if($rolUsuario2 == "personal"){
                header('Location: ../'.$ruta.'/equipo.php?noCambio=true');
            }else{
                header('Location: ../'.$ruta.'/carpeta-equipo.php?noCambio=true');
            }
        }
    }else{
        if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico") {
            header('Location: ../'.$ruta.'/equipo.php?noIdEquipo=true');
        }else if($idDepartamento == 6){
            header('Location: ../'.$ruta.'/equipo-inge-clinica.php?noIdEquipo=true');
        }else if($rolUsuario2 == "personal"){
            header('Location: ../'.$ruta.'/equipo.php?noIdEquipo=true');
        }else{
            header('Location: ../'.$ruta.'/carpeta-equipo.php?noIdEquipo=true');
        }
    }
}


