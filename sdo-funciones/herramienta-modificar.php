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

    $sqlConsultaHerra = "SELECT * FROM pañol_herramientas";
    $resSqlConsultaHerra = mysqli_query($conexion, $sqlConsultaHerra);

    while($rowSqlConsultaHerra = $resSqlConsultaHerra->fetch_assoc()){

        $datos = [];

        $datos['codInventario'] = $rowSqlConsultaHerra['codInventario'];
        $datos['numSerie'] = $rowSqlConsultaHerra['numSerie'];
        $datos['nomHerramienta'] = $rowSqlConsultaHerra['nomHerramienta'];
        $datos['marcaHerramienta'] = $rowSqlConsultaHerra['marcaHerramienta'];
        $datos['modeloHerramienta'] = $rowSqlConsultaHerra['modeloHerramienta'];
        $datos['cantHerramienta'] = $rowSqlConsultaHerra['cantHerramienta'];
        $datos['descriptHerramienta'] = $rowSqlConsultaHerra['descriptHerramienta'];
        $datos['sector'] = $rowSqlConsultaHerra['sector'];

        $codInventarioAnt = $datos['codInventario'];
        $numSerie = $datos['numSerie'];
        $nomHerramienta = $datos['nomHerramienta'];
        $marcaHerramienta = $datos['marcaHerramienta'];
        $modeloHerramienta = $datos['modeloHerramienta'];
        $cantHerramienta = $datos['cantHerramienta'];
        $descriptHerramienta = $datos['descriptHerramienta'];
        $sectorAnt = $datos['sector'];



    }


    $idHerramienta1 = $_POST['idHerramienta'];
    $idHerramienta = $_POST['codHerramienta'];
    //$nuevaCantidad = trim($_POST['total']);

    if(!empty($idHerramienta)){

        $nombre = $_POST['nomHerramienta'];
        //$categoria = $_POST['idCategoria'];
        $marca = $_POST['marcaHerramienta'];
        $cantidad = $_POST['cantidadHerramienta'];
        $numSerie = $_POST['numSerie'];
        $modelo = $_POST['modeloHerramienta'];
        $descripcion = $_POST['descripHerramienta'];
        $imagen = $_FILES['imagen'];
        $codInventario = $_POST['codInventario'];
        $sector = trim($_POST['sector']);
        $nuevoDepa = $_POST['nuevoDepa'];

        $depaActual = $_POST['idDepaActual'];

        // var_dump($depaActual);
        // var_dump($nuevoDepa);
        // exit;

        if($nuevoDepa !== null){
            $depaActual = $nuevoDepa;
        }

        if(!$imagen['tmp_name'] == ""){

            
            if($imagen['type'] == 'image/png' || $imagen['type'] == 'image/jpeg' || $imagen['type'] == 'image/jpg'){

                //CREA CARPETA 
                $carpetaIMG = '../sdo-bodega-panol/img3/';
    
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
                header('Location: ../'. $ruta . '/bodega-administrar.php?solojpeg=true');
            }

            $queryConImagen = "UPDATE pañol_herramientas SET  idDepartamento = '${depaActual}', sector = '${sector}', numSerie = '${numSerie}',imagen = '${nomArchivo}', nomHerramienta = '${nombre}' , marcaHerramienta = '${marca}' , modeloHerramienta = '${modelo}', cantHerramienta = '${cantidad}', descriptHerramienta = '${descripcion}', codInventario = '${codInventario}' WHERE codHerramienta = '${idHerramienta}'"; 
            $resultadoConImagen = mysqli_query($conexion, $queryConImagen);

        }

        if($imagen['tmp_name'] == ""){
              //Modifica la tabla de la base de datos según el WHERE
            $query = "UPDATE pañol_herramientas SET idDepartamento = '${depaActual}', sector = '${sector}', nomHerramienta = '${nombre}' ,numSerie = '${numSerie}' ,  marcaHerramienta = '${marca}' , modeloHerramienta = '${modelo}', cantHerramienta = '${cantidad}', descriptHerramienta = '${descripcion}', codInventario = '${codInventario}' WHERE codHerramienta = '${idHerramienta}'"; 
            $resultado = mysqli_query($conexion, $query);
    
        }


        //INSERT HISTORIAL DE LA HERRAMIENTA

        $tipoActualizacion = "Modificacion";

        $detalles = " 
            Datos Antes del cambio:

                ->Código Inventario: ${codInventarioAnt}
                ->Número Serie: ${numSerie}
                ->Nombre: ${nomHerramienta}
                ->Marca: ${marcaHerramienta}
                ->Modelo: ${modeloHerramienta}
                ->Sector: ${sectorAnt}
                
                          
        ";

        $detalles1 = " 
            Nuevos Datos:
                
                ->Código Inventario: ${codInventario}
                ->Número Serie: ${numSerie}
                ->Nombre: ${nombre}
                ->Marca: ${marca}
                ->Modelo: ${modelo}
                ->Sector: ${sector}

                
                          
        ";

        $sqlInsertHis = "INSERT INTO historial_estado_herramienta (tipoActualizacion, cantidad, idHerramienta, detalles, detalles1)";
        $sqlInsertHis .=  "VALUES('${tipoActualizacion}', ${cantidad}, ${idHerramienta1},' ${detalles}',' ${detalles1}')";

        // var_dump($sqlInsertHis);
        // exit;

        $resInsertHis = mysqli_query($conexion, $sqlInsertHis);
        
        header('Location: ../'. $ruta  .'/listado-herramientas.php?modificacion=true');
      
       
    
        
          
       

        
    }

}
mysqli_close($conexion);

?>




