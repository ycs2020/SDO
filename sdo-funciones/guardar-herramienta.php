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
}else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $cod = trim($_POST['codBarra']);
    $nombre = trim($_POST['nombre']);
    $imagen = $_FILES['imagen'];
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['modelo']);
    $cantidad = trim($_POST['cantidad']);
    $descripcion = trim($_POST['descripcion']);
    $depa = trim($_POST['departamento']);
    $codInventario = trim($_POST['codInventario']);
    $numSerie = trim($_POST['numSerie']);
    $fecha = date('d/m/Y');
    $sector = trim($_POST['sector']);

    if(empty($marca)){

        $marca = "Sin Información";
    }

    if(empty($modelo)){

        $modelo = "Sin Información";
    }

    if(empty($descripcion)){

        $descripcion = "SIN INFORMACIÓN";
    }

    if($numSerie == ''){

        $numSerie = 'No Aplica';
    }

    if($codInventario == ''){

        $codInventario = 'No Aplica';
    }

    if($sector == ''){

        $sector = 'No Indicado';
    }

    //LIMPIAR VARIABLES STRING - ESCAPAR LAS COMILLAS PARA QUE SE HAN TRATADAS COMO STRING
    $nombre = str_replace("'", "\'", $nombre);
    $marca = str_replace("'", "\'", $marca);
    $modelo = str_replace("'", "\'", $modelo);
    $numSerie = str_replace("'", "\'", $numSerie);
    $descripcion = str_replace("'", "\'", $descripcion);

    if(!empty($cod) && !empty($nombre)  && !empty($modelo) && !empty($cantidad) && !empty($descripcion) ){

        //Valida que la herramienta no este ingresada 
        $query = "SELECT * FROM pañol_herramientas WHERE codHerramienta = '${cod}'"; 
        $resultado = mysqli_query($conexion, $query);
        
        if($resultado->num_rows){  

            header('Location: ../'. $ruta . '/bodega-administrar.php?repetido=true');

        }else{

            if($imagen['type'] == 'image/png' || $imagen['type'] == 'image/jpeg'  ||  $imagen['type'] == 'image/jpg'){

                //CREA CARPETA 
                $carpetaIMG = '../sdo-bodega-panol/img3/';
    
                if(!is_dir($carpetaIMG)){
                    mkdir($carpetaIMG);
                }

                //Generar un nombre único
                $nomArchivo = md5( uniqid(rand(), true) ) . ".jpeg";

                //SUBIR PDF
                //$carpeta = "/pdfSoliReserva"; 
                move_uploaded_file($imagen['tmp_name'], $carpetaIMG . $nomArchivo);

                $query = "INSERT INTO pañol_herramientas(imagen, codHerramienta, codInventario, numSerie, nomHerramienta, marcaHerramienta, modeloHerramienta, cantHerramienta ,descriptHerramienta, idDepartamento, rolUsuarioIngresa,idUsuarioIngresa, sector)";
                $query .=  "VALUES('$nomArchivo','$cod', '$codInventario','$numSerie','$nombre',  '$marca', '$modelo', '$cantidad','$descripcion','$depa', '$rolUsuario','$idUsuario','$sector')";
                $resultado = mysqli_query($conexion, $query);

                $idHerramienta = mysqli_insert_id($conexion);

                $query = "INSERT INTO pañol_herramientas_historial( idHerramientaIngreso,imagen, codHerramienta, codInventario, numSerie, nomHerramienta,  marcaHerramienta, modeloHerramienta, cantHerramienta ,descriptHerramienta, idDepartamento, rolUsuarioIngresa,idUsuarioIngresa, sector)";
                $query .=  "VALUES( '$idHerramienta','$nomArchivo','$cod', '$codInventario', '$numSerie','$nombre',  '$marca', '$modelo', '$cantidad','$descripcion', '$depa', '$rolUsuario','$idUsuario','$sector')";
                $res = mysqli_query($conexion, $query);

                //INGRESO HISTORIAL HERRAMIENTA
                $detalles = "
                Nueva herramienta Añadida, desde aquí comienza su seguimiento de estados.

                   ->Ingresado Por: $nomUsuario
                   ->Departamento: $rolUsuario
                   ->Fecha Ingreso: $fecha
                   
                ";
                $tipoActualizacion = "Ingreso";

                $queryHis = "INSERT INTO historial_estado_herramienta( tipoActualizacion, cantidad, idHerramienta, detalles)";
                $queryHis .= "VALUES( '$tipoActualizacion', '$cantidad', '$idHerramienta', '$detalles')";

                $resHis = mysqli_query($conexion, $queryHis);

                if($resultado){

                    header('Location: ../' .   $ruta .'/listado-herramientas.php?exitoCrearHerramienta=true');
    
                }else{
                    echo 'Error';
                }

            }else{
    
                header('Location: ../'.  $ruta . '/bodega-administrar.php?solojpeg=true');
            }

            //Codigo para agrear una nueva categoria
            // if ($categoriaOrden == 0 || $categoriaOrden == 'Otro' || $categoriaOrden == 'otro') {
            //     if (isset($_POST['OtroCategoria'])) {
        
            //         $OtroCategoria = trim($_POST['OtroCategoria']);
        
            //         if (!empty($OtroCategoria)) {
            //             $OtroCategoria = str_replace("'", "\'", $OtroCategoria);
        
            //             $buscarCategoria = buscarCategoria($OtroCategoria);
        
            //             if($buscarCategoria == 0){
        
            //                 $queryOtroCategoria = "INSERT INTO categorias_herramienta(idCategoria,nomCategoria) VALUES (NULL,'" . $OtroCategoria . "')";
        
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
    }else{
        header('Location: ../'. $ruta . '/bodega-administrar.php?vacios=true');
    }
}
mysqli_close($conexion);
