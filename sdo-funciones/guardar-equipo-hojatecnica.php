<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

if(isset($_SESSION['idDepartamento'])){
    $idDepartamento = $_SESSION['idDepartamento'];
}


$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $idDepartamento = trim($_SESSION['idDepartamento']);
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $ruta = "sdo-jefestecnicos";
    $idDepartamento = $_SESSION['idDepartamento'];
} else if ($rolUsuario2 == "administrador") {
    $idDepartamento = 1;
    $tipoUsuario = 'administrador';
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $ruta = "sdo-directiva";
    $idDepartamento = 1;
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['idHoja']) {

        $idHoja = $_POST['idHoja'];

        //RECIBE Y GUARDA EN VARIABLES LOS DATOS DEL FORMULARIO
        $tipoMantencion = $_POST['tipoMantencion'];
        $ejecutador = $_POST['ejecutador'];
        $trabajo = $_POST['trabajo'];
        $problema = $_POST['problema'];
        $acciones = $_POST['acciones'];
        $radio = $_POST['radio'];
        $archivos = $_FILES['adjuntosTecnica'];
        $ordenCompra = $_POST['ordenCompra'];
        $otroTipoTrabajo = $_POST['otroTipoTrabajo'];
        $observaciones = $_POST['observaciones'];

        $sqlInsertHT = "INSERT INTO hoja_tecnica (idHoja,tipoMantencion,ejecutadoPor,trabajoRealizado,problema,accionesRealizadas,tipoTrabajo, ordenCompra,otroTipoTrabajo,folioHojaTec, observaciones,idCreadoPor, idDepaCreador, tipoUsuario) VALUES ($idHoja,'$tipoMantencion','$ejecutador','$trabajo','$problema','$acciones','$radio','$ordenCompra','$otroTipoTrabajo', 1,'$observaciones',$idUsuario, $idDepartamento,'$tipoUsuario')";
        $resInsertHT = mysqli_query($conexion, $sqlInsertHT);

        $idHojaTec = mysqli_insert_id($conexion);

        if ($idHojaTec < 10) {
            $folioUnico = "000" . $idHojaTec;
        } else if ($idHojaTec > 9 && $idHojaTec < 100) {
            $folioUnico = "00" . $idHojaTec;
        } else if ($idHojaTec > 99 && $idHojaTec < 1000) {
            $folioUnico = "0" . $idHojaTec;
        } else if ($idHojaTec > 999) {
            $folioUnico = $idHojaTec;
        } else {
            $folioUnico = $idHojaTec;
        }

        $folio = "HT-" .  $folioUnico;

        $sqlUpdate = "UPDATE hoja_tecnica SET folioHojaTec = '$folio' WHERE idHojaTec = $idHojaTec";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

       
        if (isset($_POST['especiesHT'], $_POST['cantidad'])) {

            $especiesHT = $_POST['especiesHT'];
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
       
            if (!empty($especiesHT) && !empty($cantidad) ) {

                for ($i = 0; $i < count($especiesHT); $i++) {
                    if (!empty($especiesHT[$i])) {
                        $especiesHTFor = $especiesHT[$i];
                        $cantidadFor = $cantidad[$i];
                        $precioFor = $precio[$i];


                        $queryMaterialSC = "INSERT INTO material_hojatecnica(nomEspecieHT,cantidad, precio,idHojaTec)
                        VALUES('$especiesHTFor', $cantidadFor,$precioFor, $idHojaTec)";

                        $resMaterialSC = mysqli_query($conexion, $queryMaterialSC);

                        if ($resMaterialSC) {
                            $usoMaterial = "Con";
                        } else {
                            $usoMaterial = "Sin";
                        }
                        // echo $queryMaterialSC;
                    } else {
                        $usoMaterial = "Sin";
                    }
                }
            } else {
                $usoMaterial = "Sin";
            }
        } else {
            $usoMaterial = "Sin";
        }

        if ($archivos) {

            $count = 0;
            foreach ($_FILES['adjuntosTecnica']['tmp_name'] as $tmpFilee) {
                if (is_uploaded_file($tmpFilee)) {
                    $count++;
                }
            }

            if ($count > 0) {

                for ($i = 0; $i < $count; $i++) {
                    $tmp_name = $_FILES["adjuntosTecnica"]["tmp_name"][$i];
                    $name = $_FILES["adjuntosTecnica"]["name"][$i];

                    if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                        echo "Vacios";
                        exit;
                    } else {

                        $directorio = "../sdo-archivosHojasTecnicas/HOJA-" . $idHojaTec . "/";
                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777);
                        }
                       
                        $dir = opendir($directorio);

                        $rutaArchivo = $directorio . $name;

                        $separarNombre = explode(".", $name);

                        $extImagen = end($separarNombre);

                        $extImagen = mb_strtolower($extImagen);

                        if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml") {

                            if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                chmod($rutaArchivo, 0777);

                                $queryArchivos = "INSERT INTO archivos_hoja_tecnica(idArchivoHT ,nomArchivoHT,rutaArchivoHT, idHojaTec) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $idHojaTec . "')";
                                $resArchivos = mysqli_query($conexion, $queryArchivos);
                                // echo $queryArchivos;
                                // exit;
                            } else {
                                header('Location: ../' . $ruta . '/ingresar-equipo-hojadevida.php?noSubido=true');
                            }
                        } else {
                            header('Location: ../' . $ruta . '/ingresar-equipo-hojadevida.php?formatoNoValido=true');
                        }
                    }
                }
            } else {
                header('Location: ../' . $ruta . '/ingresar-equipo-hojadevida.php?noAdjunto=true');
            }
        } else {
            echo "no entra";
            exit;
        }
        if($resInsertHT){
            header('Location: ../' . $ruta . '/consultar-equipo.php?exitoTecnica=true');
        }else{
            header('Location: ../' . $ruta . '/consultar-equipo.php?errorTecnica=true');
        }
    } else {
        header('Location: ../' . $ruta . '/consultar-equipo.php?noId=true');
    }
}
