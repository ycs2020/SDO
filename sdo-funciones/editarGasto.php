<?php

include 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idGasto = $_POST['idGasto'];
    $idHoja = $_POST['idHoja'];

    // echo $idGasto . "<br>";
    // echo $idHoja;

    if (!empty($idGasto) && !empty($idHoja)) {

        /** GUARDA LOS DATOS ENVIADOS POR MÉTODO POST EN VARIABLES */
        $nomMantenimiento = trim($_POST['nomMantenimiento']);
        $idHojaTecnica = $_POST['idHojaTecnica'];
        $tipoMantencion = $_POST['tipoMantencion'];
        $ordenCompra = $_POST['ordenCompra'];
        $codOrdenCompra = $_POST['codOrdenCompra'];
        $costoTotal = $_POST['costoTotal'];
        $mesGasto = $_POST['mesGasto'];
        $encargadoMant = $_POST['encargadoMant'];
        $fechaGasto = $_POST['fechaGasto'];

        $numMes = date("m", strtotime($fechaGasto));

        if($numMes == '01'){
            $mesGasto =  'Enero';
        }else if($numMes == '02'){
            $mesGasto =  'Febrero';
        }else if($numMes == '03'){
            $mesGasto =  'Marzo';
        }else if($numMes == '04'){
            $mesGasto =  'Abril';
        }else if($numMes == '05'){
            $mesGasto =  'Mayo';
        }else if($numMes == '06'){
            $mesGasto =  'Junio';
        }else if($numMes == '07'){
            $mesGasto =  'Julio';
        }else if($numMes == '08'){
            $mesGasto =  'Agosto';
        }else if($numMes == '09'){
            $mesGasto =  'Septiembre';
        }else if($numMes == '10'){
            $mesGasto =  'Octubre';
        }else if($numMes == '11'){
            $mesGasto =  'Noviembre';
        }else if($numMes == '12'){
            $mesGasto =  'Diciembre';
        }


        if (empty($idHojaTecnica)) {
            $idHojaTecnica = 'NULL';
        }
        if (empty($ordenCompra)) {
            $ordenCompra = 'NULL';
        }

        //SQL UPDATE - TABLA GASTO EQUIPOS
        $sqlUpdate = "UPDATE gastoEquipos SET fechaGasto = '$fechaGasto', mesGasto = '$mesGasto',  nomMantenimiento =  '$nomMantenimiento',  idHojaTecnica =  $idHojaTecnica, tipoMantencion =  '$tipoMantencion', codOrdenCompra =  '$codOrdenCompra', solicitudCompra = $ordenCompra ,costoTotal = $costoTotal, encargadoMant = '$encargadoMant' WHERE idGasto = $idGasto";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        $archivo = $_FILES['archivoOC'];

        if (!empty($archivo['name'])) {

            //SELECCIONAMOS EL ARCHIVO
            $sqlSelect = "SELECT * FROM archivos_gasto_equipos WHERE idGasto = $idGasto";
            $resSelect = mysqli_query($conexion, $sqlSelect);

            while ($rowSelect = $resSelect->fetch_assoc()) {
                $datos = [];
                $datos['rutaArchivoGE'] = $rowSelect['rutaArchivoGE'];
                $rutaArchivoGE = $datos['rutaArchivoGE'];

                if (!empty($rutaArchivoGE)) {
                    unlink($rutaArchivoGE);
                } else {
                    echo "No borrado";
                }
            }

            //ELIMINA EL ARCHIVO ANTERIOR 
            $sqlDelete = "DELETE  FROM archivos_gasto_equipos WHERE idGasto = $idGasto ";
            $resDelete = mysqli_query($conexion, $sqlDelete);

            //SUBE NUEVO ARCHIVO
            if (isset($_FILES['archivoOC'])) {
                $directorio = "../sdo-archivos-gastoEquipos/EQUIPO-" . $idHoja . "/Gasto-" . $idGasto . "/";
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }
            }

            if (isset($_FILES["archivoOC"])) {
                $tot = count($_FILES["archivoOC"]["name"]);

                for ($i = 0; $i < $tot; $i++) {
                    $tmp_name = $_FILES["archivoOC"]["tmp_name"];
                    $name = $_FILES["archivoOC"]["name"];

                    if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                        echo 'sin nombre';
                    } else {

                        $directorio = "../sdo-archivos-gastoEquipos/EQUIPO-" . $idHoja . "/Gasto-" . $idGasto . "/";

                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777, true);
                        }

                        $dir = opendir($directorio);

                        $ruta = $directorio . $name;

                        $separarNombre = explode(".", $name);

                        $extImagen = end($separarNombre);

                        $extImagen = mb_strtolower($extImagen);

                        if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {

                            if (move_uploaded_file($tmp_name, $ruta)) {

                                $query = "INSERT INTO archivos_gasto_equipos( nomArchivoGE,rutaArchivoGE,extArchivo, idGasto)";
                                $query .=  "VALUES('$name', '$ruta','$extImagen', $idGasto )";
                                $resArchivo = mysqli_query($conexion, $query);

                                if ($resArchivo) {
                                    echo "GOOD SUBIDA";
                                }
                            } else {
                                echo 'No subi';
                                exit;
                            }
                        }
                    }
                }
            }
        } else {
           echo "no archivo ok";
        }

        if ($resUpdate) {
            header('Location: ../' . $rutaDireccion . '/gastoEquipo.php?exitoModificar=true&&idHoja=' . $idHoja);
        } else {
            header('Location: ../' . $rutaDireccion . '/gastoEquipo.php?errorModificar=true&&idHoja=' . $idHoja);
        }
    } else {
        echo "ESTAN VACIOS EL idGasto y idHoja";
        exit;
    }
} else {
    echo "NO ES POST EL ENVIO DE LOS ARCHIVOS";
    exit;
}
