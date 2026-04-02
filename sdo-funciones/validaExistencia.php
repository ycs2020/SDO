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
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['codBarra'])) {
    $buscCodigo = trim($_POST['codBarra']);
    $buscCodigo1 = trim($_POST['codInventario']);

    if (!empty($buscCodigo) || !$buscCodigo == '0' || !empty($buscCodigo1) || !$buscCodigo1 == '0') {

        $sqlquery = "SELECT * FROM pañol_herramientas WHERE codHerramienta = '${buscCodigo}' OR codInventario = '${buscCodigo1}' ";
        $res = mysqli_query($conexion, $sqlquery);


        if ($res->num_rows) {

            while ($row = $res->fetch_assoc()) {

                $datos = [];

                $datos['idHerramienta'] = $row['idHerramienta'];
                $datos['imagen'] = $row['imagen'];
                $datos['nomHerramienta'] = $row['nomHerramienta'];
                $datos['codHerramienta'] = $row['codHerramienta'];
                $datos['codInventario'] = $row['codInventario'];
                $datos['cantHerramienta'] = $row['cantHerramienta'];
                $datos['sector'] = $row['sector'];


                $idHerramienta = $datos['idHerramienta'];
                $imagen = $datos['imagen'];
                $nomHerramienta = $datos['nomHerramienta'];
                $codHerramienta = $datos['codHerramienta'];
                $codInventario = $datos['codInventario'];
                $cantHerramienta = $datos['cantHerramienta'];
                $sector = $datos['sector'];

                
            }

            if($cantHerramienta == 0){

                $estado = 'En Arriendo';
            }

            if($cantHerramienta == -1){

                $estado = 'Entrega Definitiva';
            }

            if($estado == 'Entrega Definitiva'){
                header('Location: ../'. $ruta .'/arriendo-retiro.php?entregado=true');
                return;
            }

            if($estado == 'En Arriendo'){
                header('Location: ../'. $ruta .'/arriendo-retiro.php?sinStock=true');
                return;
            }


            if (!isset($_SESSION['CARRITOHERRARETIRO'])) {

                $herramientaRetiro = array(

                    'id' => $idHerramienta,
                    'imagen' => $imagen,
                    'nomHerramienta' => $nomHerramienta,
                    'codHerramienta' => $codHerramienta,
                    'codInventario' => $codInventario,
                    'cantHerramienta' => $cantHerramienta,
                    'sacar' => 1,
                    'sector' => $sector

                );

                $_SESSION['CARRITOHERRARETIRO'][0] = $herramientaRetiro;
                header('Location: ../'. $ruta .'/arriendo-retiro.php?ingresado=true');
            } else {

                $idHerramientas = array_column($_SESSION['CARRITOHERRARETIRO'], 'id');

                if (in_array($idHerramienta, $idHerramientas)) {


                    header('Location: ../'. $ruta  .'/arriendo-retiro.php?repetido=true');
                    return;
                } else {
                    $numeroHerramientas = count($_SESSION['CARRITOHERRARETIRO']);
                    $herramientaRetiro = array(

                        'id' => $idHerramienta,
                        'imagen' => $imagen,
                        'nomHerramienta' => $nomHerramienta,
                        'codHerramienta' => $codHerramienta,
                        'codInventario' => $codInventario,
                        'cantHerramienta' => $cantHerramienta,
                        'sacar' => 1,
                        'sector' => $sector


                    );


                    array_push($_SESSION['CARRITOHERRARETIRO'], $herramientaRetiro);
                    header('Location: ../'. $ruta  .'/arriendo-retiro.php?ingresado=true');

                }
            }

        } else {
            header('Location: ../'. $ruta .'/arriendo-retiro.php?noExiste');
        }
    } else {

        header('Location: ../'. $ruta .'/arriendo-retiro.php?exlusiones=true');
    }
}
//mysqli_close($conexion);
?>

<?php

// // ASIGNAR ID
// include '../sdo-funciones/conexion.php';
// include '../sdo-funciones/funciones.php';

?>

