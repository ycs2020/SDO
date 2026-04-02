<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


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
    header('Location: ../sdo-flujo/sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){


     //OBIENE LOS DATOS ANTES DEL LA ACTUALIZACIÓN

     $sqlConsultaMate = "SELECT * FROM pañol_materiales";
     $resSqlConsultaMate = mysqli_query($conexion, $sqlConsultaMate);
 
     while($rowSqlConsultaMate = $resSqlConsultaMate->fetch_assoc()){
 
         $datos = [];
 
         $datos['codInventario'] = $rowSqlConsultaMate['codInventario'];
         $datos['numSerie'] = $rowSqlConsultaMate['numSerie'];
         $datos['nomMaterial'] = $rowSqlConsultaMate['nomMaterial'];
         $datos['marcaMaterial'] = $rowSqlConsultaMate['marcaMaterial'];
         $datos['modeloMaterial'] = $rowSqlConsultaMate['modeloMaterial'];
         $datos['cantMaterial'] = $rowSqlConsultaMate['cantMaterial'];
         $datos['descriptMaterial'] = $rowSqlConsultaMate['descriptMaterial'];
         $datos['sector'] = $rowSqlConsultaMate['sector'];

 
         $codInventarioAnt = $datos['codInventario'];
         $numSerie = $datos['numSerie'];
         $nomMaterial = $datos['nomMaterial'];
         $marcaMaterial = $datos['marcaMaterial'];
         $modeloMaterial = $datos['modeloMaterial'];
         $cantMaterial = $datos['cantMaterial'];
         $descriptMaterial = $datos['descriptMaterial'];
         $sectorAnt = $datos['sector'];

 
 
 
     }
 

    $idMaterial1 = $_POST['idMaterial'];
    $idMaterial = $_POST['codMaterial'];
    //$nuevaCantidad = trim($_POST['total']);

    if(!empty($idMaterial1)){

        $nombre = $_POST['nomMaterial'];

        $nombre = preg_replace('([^A-Za-z0-9])', '', $nombre);

        $codInventario = $_POST['codInventario'];
        $numSerie = $_POST['numSerie'];
        $imagen = $_FILES['imagen'];
        //$categoria = $_POST['idCategoria'];
        $marca = $_POST['marcaMaterial'];
        $cantidad = $_POST['cantMaterial'];
        $sector = trim($_POST['sector']);

        if($_POST['anadir'] !== ''){

            $nuevaCantidad = $_POST['anadir'];

            $cantidad =  $cantidad + $nuevaCantidad;

        }

        var_dump($cantidad);

        $modelo = $_POST['modeloMaterial'];
        $descripcion = $_POST['descripMaterial'];

        if(!$imagen['tmp_name'] == ""){

            
            if($imagen['type'] == 'image/png' || $imagen['type'] == 'image/jpeg' || $imagen['type'] == 'image/jpg'){

                //CREA CARPETA 
                $carpetaIMG = '../sdo-bodega-panol/img/';
    
                if(!is_dir($carpetaIMG)){
                    mkdir($carpetaIMG);
                }

                // var_dump($imagen);
                // exit;
    
                //Generar un nombre único
                $nomArchivo = md5( uniqid(rand(), true) ) . ".jpeg";
    
                //SUBIR PDF
                //$carpeta = "/pdfSoliReserva"; 
                move_uploaded_file($imagen['tmp_name'], $carpetaIMG . $nomArchivo);
            }else{
                header('Location: ../'. $ruta   .'/listado-materiales.php?solojpeg=true');
            }

            $queryConImagen = "UPDATE pañol_materiales SET   sector = '${sector}', numSerie = '${numSerie}',imagen = '${nomArchivo}', nomMaterial = '${nombre}' , marcaMaterial = '${marca}' , modeloMaterial = '${modelo}', cantMaterial =${cantidad}, descriptMaterial = '${descripcion}', codInventario = '${codInventario}' WHERE codMaterial = '${idMaterial}'"; 
            $resultadoConImagen = mysqli_query($conexion, $queryConImagen);

        }

        if($imagen['tmp_name'] == ""){
            //Modifica la tabla de la base de datos según el WHERE
            $query = "UPDATE pañol_materiales SET   sector = '${sector}', numSerie = '${numSerie}', nomMaterial = '${nombre}' , marcaMaterial = '${marca}' , modeloMaterial = '${modelo}', cantMaterial = ${cantidad}, descriptMaterial = '${descripcion}', codInventario = '${codInventario}' WHERE codMaterial = '${idMaterial}'"; 
            $resultado = mysqli_query($conexion, $query);  
      }
        

        //INSERT HISTORIAL DE LA HERRAMIENTA

        $tipoActualizacion = "Modificacion";

        $detalles = " 
             Datos Antes del cambio:
 
                 ->Código Inventario: ${codInventarioAnt}
                 ->Número Serie: ${numSerie}
                 ->Nombre: ${nomMaterial}
                 ->Cantidad: ${cantMaterial}
                 ->Marca: ${marcaMaterial}
                 ->Modelo: ${modeloMaterial}
                 ->Sector: ${sectorAnt}
                 
                           
        ";
 
        $detalles1 = " 
             Nuevos Datos:
                 
                 ->Código Inventario: ${codInventario}
                 ->Número Serie: ${numSerie}
                 ->Nombre: ${nombre}
                 ->Cantidad: ${cantidad}
                 ->Marca: ${marca}
                 ->Modelo: ${modelo}
                 ->Sector: ${sector}

                 
                 
                           
         ";
 
        $sqlInsertHis = "INSERT INTO historial_estado_material (tipoActualizacion, cantidad, idMaterial, detalles, detalles1)";
        $sqlInsertHis .=  "VALUES('${tipoActualizacion}', ${cantidad}, '${idMaterial1}',' ${detalles}',' ${detalles1}')";
 
         // var_dump($sqlInsertHis);
         // exit;
 
        $resInsertHis = mysqli_query($conexion, $sqlInsertHis);
         
        header('Location: ../'. $ruta  .'/listado-materiales.php?modificacion=true');
       

    
    }

}
mysqli_close($conexion);
