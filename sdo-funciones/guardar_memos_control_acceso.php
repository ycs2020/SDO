<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

$total = 0;

if(isset($_POST["numMemo"],$_POST["nomEmisor"],$_POST["fechaRecepcion"],$_POST["fechaEmision"],$_POST['nomUnidad'])){
    $contarfuncionario = $_POST['contarfuncionario'];
    $numMemo = $_POST["numMemo"];
    $nombreEmisor = $_POST["nomEmisor"];
    $fechaRecepcion = $_POST["fechaRecepcion"];
    $fechaEmision = $_POST["fechaEmision"];
    $nomUnidad = $_POST["nomUnidad"];


    $sqlBuscarCoincidencias = "SELECT COUNT(*) as total FROM memos_control_acceso WHERE numeroMemo = $numMemo AND nomEmisor LIKE '%$nombreEmisor%' AND unidad LIKE '%$nomUnidad%' AND fechaEmision = '$fechaEmision'";
    $resBuscarCoincidencias = mysqli_query($conexion, $sqlBuscarCoincidencias);
    while($rowBuscarCoincidencias = $resBuscarCoincidencias->fetch_assoc()){
        $total = $rowBuscarCoincidencias["total"];
    }


    if($total > 0){
        header('Location: ../'. $rutaDireccion .'/memo_control_acceso.php?errorArchivoSubido=true');
        exit();
    }else{

        $sql = "INSERT INTO `memos_control_acceso`(`nomEmisor`, `unidad`, `fechaRecepcion`, `fechaEmision`, `numeroMemo`) 
        VALUES ('$nombreEmisor','$nomUnidad','$fechaRecepcion','$fechaEmision','$numMemo')";
        $res = mysqli_query($conexion,$sql);

        if($res){
            $id = mysqli_insert_id($conexion);

            for($i = 1; $i <= $contarfuncionario; $i++){
                $nomFuncionario = $_POST['nomFuncionario'.$i];
                $rutFuncionario = $_POST['rutFuncionario'.$i];

                $sqlInsert = "INSERT INTO `memos_funcionarios`(`nomFuncionario`, `rutFuncionario`, `idMemo`) 
            VALUES ('$nomFuncionario','$rutFuncionario','$id')";
                $resInsert = mysqli_query($conexion,$sqlInsert);

            }

            if (empty($_FILES["archivos"])) {
                echo 'Sin Archivos';
                exit;
            }else{
                $count = 0;
                foreach ($_FILES['archivos']['tmp_name'] as $tmpFilee) {
                    if (is_uploaded_file($tmpFilee)) {
                        $count++;
                    }
                }
                if ($count > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $tmp_name = $_FILES["archivos"]["tmp_name"][$i];
                        $name = $_FILES["archivos"]["name"][$i];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo "Vacios";
                        } else {
                            $directorio = "../sdo-memo-control/carpeta-" . $id. '/';
                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777, true);
                            }
                            $dir = opendir($directorio);

                            $rutaArchivo = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);
                            if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml") {
                                if (move_uploaded_file($tmp_name, $rutaArchivo)) {
                                    chmod($rutaArchivo, 0777);
                                    $sqlArchivos = "INSERT INTO `archivos_memo_control_acceso`(`nomArchivo`, `rutaArchivo`, `tipoArchivo`, `idMemo`) 
                                    VALUES ('$name','$rutaArchivo','$extImagen','$id')";
                                    $resArchivos = mysqli_query($conexion,$sqlArchivos);
                                    
                                }else{
                                    echo "FALLO EN LA SUBIDA DE archivos";
                                    exit;
                                }
                            }
                        }

                    }
                }else{
                    echo 'error al contar cantidad de archivos';
                    exit;
                }
            }
            header('Location: ../' . $rutaDireccion . '/memo_control_acceso.php?registroExitoso=true');
            exit();
        }else{
            header('Location: ../'. $rutaDireccion .'/memo_control_acceso.php?error=true');
            exit();
        }
    }

}else{
    header('Location: ../'. $rutaDireccion .'/memo_control_acceso.php?error1=true');
    exit();
}