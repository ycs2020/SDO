<?php
// Mostrar todos los errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$rutaUsuario = "";

if ($rolUsuario2 == "personal") {
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaUsuario = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaUsuario = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

// Incluir la conexión a la base de datos y otras funciones si es necesario
include_once 'conexion.php';
include_once 'funciones.php';

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_CL", 'Spanish_Chile', 'Spanish');
setlocale(LC_MONETARY, 'es_CL');

$fechaNow = date('Y-m-d H:i:s');

if(isset($_POST['idActa'])){

    $idActa = $_POST['idActa'];

    if($idActa > 0){
        if(isset($_POST['nomUsuario'],$_POST['funcionarioBloqueo'],$_POST['rutFuncionarioBloqueo'],$_POST['estadoCCAA'],$_POST['estadoActas'],$_POST['funcionarioSolicitante'],$_POST['detalleObs'])){

            $nomUsuario = $_POST['nomUsuario'];
            $funcionarioBloqueo = $_POST['funcionarioBloqueo'];
            $rutFuncionarioBloqueo = $_POST['rutFuncionarioBloqueo'];
            $estadoCCAA = $_POST['estadoCCAA'];
            $estadoActas = $_POST['estadoActas'];
            $funcionarioSolicitante = $_POST['funcionarioSolicitante'];
            $detalleObs = $_POST['detalleObs'];


//            echo "Nombre de Usuario: " . $nomUsuario . "<br>";
//            echo "Funcionario Bloqueo: " . $funcionarioBloqueo . "<br>";
//            echo "RUT Funcionario Bloqueo: " . $rutFuncionarioBloqueo . "<br>";
//            echo "Estado CCAA: " . $estadoCCAA . "<br>";
//            echo "Estado Actas: " . $estadoActas . "<br>";
//            echo "Funcionario Solicitante: " . $funcionarioSolicitante . "<br>";
//            echo "Detalle Observación: " . $detalleObs . "<br>";

            if(!empty($nomUsuario) && !empty($funcionarioBloqueo) && !empty($rutFuncionarioBloqueo) && !empty($funcionarioSolicitante) && !empty($detalleObs)){

                $sqlBloqueoActa = "UPDATE actas SET bloqueoActa = 1 WHERE idActa = $idActa";

//                echo $sqlBloqueoActa;

                $resBloqueoActa = mysqli_query($conexion,$sqlBloqueoActa);

                if($resBloqueoActa){

                    $sqlObsBloqueo = "INSERT INTO actas_bloqueos(idBloqueo, folioBloqueo, fechaBloqueo, funcionarioBloqueo, rutBloqueo, actaBloqueo, creadorBloqueo, solicitanteBloqueo, detalleBloqueo, tipoBloqueo) VALUES (NULL,'','$fechaNow','$funcionarioBloqueo','$rutFuncionarioBloqueo','$idActa','$nomUsuario','$funcionarioSolicitante','$detalleObs','Bloqueo')";

                    $resObsBloqueo = mysqli_query($conexion,$sqlObsBloqueo);

                    if($resObsBloqueo){

                        $lastInsertId = mysqli_insert_id($conexion);

                        if ($lastInsertId < 10) {
                            $folioUnico = "BCA000" . $lastInsertId;
                        } else if ($lastInsertId > 9 && $lastInsertId < 100) {
                            $folioUnico = "BCA00" . $lastInsertId;
                        } else if ($lastInsertId > 99 && $lastInsertId < 1000) {
                            $folioUnico = "BCA0" . $lastInsertId;
                        } else if ($lastInsertId > 999) {
                            $folioUnico = "BCA".$lastInsertId;
                        } else {
                            $folioUnico = "BCA".$lastInsertId;
                        }

                        $sqlFolioBloqueo = "UPDATE actas_bloqueos SET folioBloqueo = '$folioUnico' WHERE idBloqueo = '$lastInsertId'";

                        $resFolioBloqueo = mysqli_query($conexion,$sqlFolioBloqueo);

                        if (isset($_FILES["archivo_bloqueo"])) {
                            // Verificamos si es un solo archivo o un conjunto de archivos
                            $files = is_array($_FILES["archivo_bloqueo"]["name"]) ? $_FILES["archivo_bloqueo"] : array_map(function($el) { return [$el]; }, $_FILES["archivo_bloqueo"]);

                            $tot = count($files["name"]);

                            for ($i = 0; $i < $tot; $i++) {
                                $tmp_name = $files["tmp_name"][$i];
                                $name = $files["name"][$i];

                                if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                                    $resultadoImagen = 'Vacio';
                                } else {
                                    $directorio = "../sdo-imagenes-bloqueo/Bloqueo-" . $lastInsertId . "/";

                                    if (!file_exists($directorio)) {
                                        mkdir($directorio, 0777);
                                    }

                                    $dir = opendir($directorio);
                                    $ruta = $directorio . $name;
                                    $separarNombre = explode(".", $name);
                                    $extImagen = mb_strtolower(end($separarNombre));

                                    if (in_array($extImagen, ["jpg", "png", "jpeg", "pdf", "eml"])) {
                                        if (move_uploaded_file($tmp_name, $ruta)) {
                                            $queryImagen = "INSERT INTO imagenes_bloqueoFunc(idImagen,nomImagen,extImagen,rutaImagen,idBloqueo) VALUES (NULL, '$name', '$extImagen', '$ruta', '$lastInsertId')";

                                            $resImagen = mysqli_query($conexion, $queryImagen);
                                            $resultadoImagen = $resImagen ? 'Correcto' : 'Error';
                                        } else {
                                            $resultadoImagen = 'Error';
                                        }
                                    } else {
                                        $resultadoImagen = 'Formato';
                                    }
                                }
                            }
                        }

                        if($resultadoImagen == 'Correcto'){
                            header("Location: ../$rutaUsuario/actas-funcionario.php?bloqueoCorrecto=true");
                        }else if($resultadoImagen == 'Error'){
                            header("Location: ../$rutaUsuario/actas-funcionario.php?bloqueoErrorImagen=true");
                        }else if($resultadoImagen == 'Formato'){
                            header("Location: ../$rutaUsuario/actas-funcionario.php?bloqueoImagenFormato=true");
                        }else {
                            header("Location: ../$rutaUsuario/actas-funcionario.php?bloqueoSinImagen=true");

                        }

                    }else{
                        header("Location: ../$rutaUsuario/actas-funcionario.php?bloqueoSinObs=true");
                    }

                }else{
                    header("Location: ../$rutaUsuario/actas-funcionario.php?errorBloqueo=true");
                }

            }else{
                header("Location: ../$rutaUsuario/actas-funcionario.php?errorBloqueo=true");

            }


        }else{
            header("Location: ../$rutaUsuario/actas-funcionario.php?errorBloqueo=true");
        }
    }else{
        header("Location: ../$rutaUsuario/actas-listado.php?errorBloqueo=true");
    }

}else{
    header("Location: ../$rutaUsuario/actas-listado.php?errorBloqueo=true");
}