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
$rolUsuario2 = strtolower($rolUsuario);

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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cod = trim($_POST['codBarra']);
    $nombre = trim($_POST['nombre']);
    $marca = trim($_POST['marca']);
    $imagen = $_FILES['imagen'];
    $modelo = trim($_POST['modelo']);
    $cantidad = trim($_POST['cantidad']);
    $descripcion = trim($_POST['descripcion']);
    $vencimiento = $_POST['vencimiento'];
    $codInventario = $_POST['codigoInventario'];
    $numSerial = $_POST['numSerial'];
    $idDepa = $_POST['departamento'];
    $sector = trim($_POST['sector']);

    if (empty($marca)) {
        $marca = "Sin Información";
    }

    if (empty($modelo)) {
        $modelo = "Sin Información";
    }

    if (empty($descripcion)) {
        $descripcion = "Sin Información";
    }

    if (empty($vencimiento)) {

        $vencimiento = "Sin vencimiento";
    }

    if ($codInventario == '') {
        $codInventario = "No Aplica";
    }

    if ($numSerial == '') {
        $numSerial = "No Aplica";
    }

    //LIMPIAR VARIABLES STRING - ESCAPAR LAS COMILLAS PARA QUE SE HAN TRATADAS COMO STRING
    $nombre = str_replace("'", "\'", $nombre);
    $marca = str_replace("'", "\'", $marca);
    $modelo = str_replace("'", "\'", $modelo);

    if (!empty($cod) && !empty($nombre) && !empty($marca) && !empty($modelo) && !empty($cantidad) && !empty($descripcion)  && !empty($codInventario)  && !empty($numSerial)) {

        //Valida que la herramienta no este ingresada 
        $query = "SELECT * FROM pañol_materiales WHERE codMaterial = '${cod}'";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado->num_rows) {

            header('Location: ../'.$ruta.'/materiales-administrar.php?repetido=true');
        } else {

            if ($imagen['type'] == 'image/png' || $imagen['type'] == 'image/jpeg' || $imagen['type'] == 'image/jpg') {

                $carpetaIMG = '../sdo-bodega-panol/img/';

                if (!is_dir($carpetaIMG)) {
                    mkdir($carpetaIMG);
                }

                //Generar un nombre único
                $nomArchivo = md5(uniqid(rand(), true)) . ".jpeg";

                //SUBIR PDF
                //$carpeta = "/pdfSoliReserva"; 
                move_uploaded_file($imagen['tmp_name'], $carpetaIMG . $nomArchivo);

                $query = "INSERT INTO pañol_materiales ( imagen,codMaterial,codInventario,numSerie, nomMaterial, marcaMaterial, modeloMaterial, cantMaterial, fechaVencimiento ,descriptMaterial, idDepartamento,rolUsuarioIngresa,idUsuarioIngresa, sector)";
                $query .=  "VALUES('$nomArchivo','$cod','$codInventario','$numSerial',  '$nombre',  '$marca', '$modelo', ${cantidad}, '$vencimiento','$descripcion', '${idDepa}', '${rolUsuario}', '${idUsuario}', '${sector}')";
                               
                $resultado = mysqli_query($conexion, $query);

                $idMaterial = mysqli_insert_id($conexion);

                //Query Insert tabla pañol_herramientas_historial
                $descripcionHis = "En la jornada " . date('Y/m/d') . " Se ingresaron " . $cantidad . " unidades del Material: " . $nombre;

                $query = "INSERT INTO pañol_materiales_historial ( idMaterialIngreso,imagen,codMaterial, codInventario,numSerie,nomMaterial,  marcaMaterial, modeloMaterial, cantMaterial, fechaVencimiento ,descriptMaterial, idDepartamento,rolUsuarioIngresa,idUsuarioIngresa, sector)";
                $query .=  "VALUES('$idMaterial','$nomArchivo','$cod','$codInventario' ,'$numSerial' ,'$nombre',  '$marca', '$modelo', {$cantidad}, '$vencimiento','$descripcion', '${idDepa}', '${rolUsuario}', '${idUsuario}', '${sector}')";      
                $resultado = mysqli_query($conexion, $query);

                //INGRESO HISTORIAL HERRAMIENTA
                $fecha = date('d/m/Y');
                $detalles = "
                    Nuevo Material Añadido, desde aquí comienza su seguimiento de estados.

                    ->Ingresado Por: $nomUsuario
                    ->Departamento: $rolUsuario
                    ->Fecha Ingreso: $fecha
                    ->Cantidad Inicial: $cantidad
                ";

                $tipoActualizacion = "Ingreso";

                $queryHis = "INSERT INTO historial_estado_material( tipoActualizacion, cantidad, idMaterial, detalles)";
                $queryHis .= "VALUES( '$tipoActualizacion', '$cantidad', '$idMaterial', '$detalles')";

                $resHis = mysqli_query($conexion, $queryHis);

                if ($resultado) {
                    header('Location: ../' .   $ruta . '/listado-materiales.php?exitoCrearMaterial=true');
                }

            } else {

                header('Location: ../'.$ruta.'/listado-materiales.php?solojpeg=true');
            }

            // if ($categoriaOrden == 0 || $categoriaOrden == 'Otro' || $categoriaOrden == 'otro') {
            //     if (isset($_POST['OtroCategoria'])) {

            //         $OtroCategoria = trim($_POST['OtroCategoria']);

            //         if (!empty($OtroCategoria)) {
            //             $OtroCategoria = str_replace("'", "\'", $OtroCategoria);

            //             $buscarCategoria = buscarCategoria($OtroCategoria);

            //             if($buscarCategoria == 0){

            //                 $queryOtroCategoria = "INSERT INTO categorias_material(idCategoria,nomCategoria) VALUES (NULL,'" . $OtroCategoria . "')";

            //                 // echo $queryOtroServicio;

            //                 $resOtroCategoria = mysqli_query($conexion, $queryOtroCategoria);

            //                 $categoriaOrden = mysqli_insert_id($conexion);
            //             }else{
            //                 $categoriaOrden = $buscarCategoria;
            //             }


            //         } else {
            //         }
            //     } else {
            //     }
            // }
        }

    } else {
        header('Location: ../'.$ruta.'/materiales-administrar.php?vacios=true');
    }

}
mysqli_close($conexion);
