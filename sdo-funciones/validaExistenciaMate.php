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

if (isset($_POST['codBarraMate'])) {
    $buscCodigo = trim($_POST['codBarraMate']);
    $buscCodigo1 = trim($_POST['codInventarioMate']);

    if (!empty($buscCodigo) || !$buscCodigo == '0' || !empty($buscCodigo1) || !$buscCodigo1 == '0') {

        $sqlquery = "SELECT * FROM pañol_materiales WHERE codMaterial = '${buscCodigo}' OR codInventario = '${buscCodigo1}' ";


        $res = mysqli_query($conexion, $sqlquery);

        if ($res->num_rows) {

            if ($res) {
                header('Location: ../' . $ruta . '/arriendo-retiro.php?yaExiste=true');
            }

            while ($row = $res->fetch_assoc()) {

                $datos = [];

                $datos['idMaterial'] = $row['idMaterial'];
                $datos['imagen'] = $row['imagen'];
                $datos['nomMaterial'] = $row['nomMaterial'];
                $datos['codMaterial'] = $row['codMaterial'];
                $datos['codInventario'] = $row['codInventario'];
                $datos['cantMaterial'] = $row['cantMaterial'];
                $datos['sector'] = $row['sector'];


                $idMaterial = $datos['idMaterial'];
                $imagen = $datos['imagen'];
                $nomMaterial = $datos['nomMaterial'];
                $codMaterial = $datos['codMaterial'];
                $codInventario = $datos['codInventario'];
                $cantMaterial = $datos['cantMaterial'];
                $sector = $datos['sector'];
            }

            if ($cantMaterial == 0) {

                $estado = 'Sin Stock';
            }

            if ($estado == 'Sin Stock') {
                header('Location: ../' . $ruta . '/arriendo-retiro.php?noTiene=true');
                return;
            }

            if (!isset($_SESSION['CARRITOMATERETIRO'])) {

                $materialRetiro = array(

                    'id' => $idMaterial,
                    'imagen' => $imagen,
                    'nomMaterial' => $nomMaterial,
                    'codMaterial' => $codMaterial,
                    'codInventario' => $codInventario,
                    'cantMaterial' => $cantMaterial,
                    'sacar' => 1,
                    'sector' => $sector

                );


                $_SESSION['CARRITOMATERETIRO'][0] = $materialRetiro;
                header('Location: ../' . $ruta . '/arriendo-retiro.php?ingresadoMate=true');
            } else {

                $idMateriales = array_column($_SESSION['CARRITOMATERETIRO'], 'id');

                if (in_array($idMaterial, $idMateriales)) {


                    header('Location: ../' . $ruta . '/arriendo-retiro.php?repetidoMate=true');
                    return;
                } else {

                    $numMateriales = count($_SESSION['CARRITOMATERETIRO']);
                    $materialRetiro = array(

                        'id' => $idMaterial,
                        'imagen' => $imagen,
                        'nomMaterial' => $nomMaterial,
                        'codMaterial' => $codMaterial,
                        'codInventario' => $codInventario,
                        'cantMaterial' => $cantMaterial,
                        'sacar' => 1,
                        'sector' => $sector


                    );


                    array_push($_SESSION['CARRITOMATERETIRO'], $materialRetiro);
                    header('Location: ../' . $ruta . '/arriendo-retiro.php?ingresadoMate=true');
                }
            }
        } else {
            header('Location: ../' . $ruta . '/arriendo-retiro.php?noExisteMate');
        }
    } else {

        header('Location: ../' . $ruta . '/arriendo-retiro.php?exlusionesMaate=true');
    }
}
//mysqli_close($conexion);
?>

<?php

// // ASIGNAR ID
// include '../sdo-funciones/conexion.php';
// include '../sdo-funciones/funciones.php';

?>

