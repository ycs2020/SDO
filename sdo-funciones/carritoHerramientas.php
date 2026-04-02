<?php

include 'conexion.php';
include 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$mensaje = "";
//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


$idDepartamento = trim($_SESSION['idDepartamento']);


//OBTIENE NOMBRE DEL DEPARTAMENTO
$sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento = ${idDepartamento}";
$res = mysqli_query($conexion, $sqlDepa);

while($row = $res->fetch_assoc()){

    $datos = [];

    $datos['nomDepartamento'] = $row['nomDepartamento'];

    $nomDepartamento = $datos['nomDepartamento'];


}

//TIPO DE USUARIO
//RUTA DE DIRECCIONAMIENTO
$ruta = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $ruta = "sdo-bodega-panol";
}else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $ruta = "sdo-administrador";
    $idDepartamento = 9;
} else if($rolUsuario2 == "directiva"){
    $tipoUsuario = 'directiva';
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['btnAccion'])) {


    switch ($_POST['btnAccion']) {

            //RECUPERAR Y GUARDAR LA INFORMACIÓN DE LA  HERRAMIENTA EN UN ARRAY 
        case 'Agregar':

            if (is_numeric($_POST['id'])) {
                $id = $_POST['id'];
                $mensaje .= "  ID: " . $id . "<br>";
            } else {
                $mensaje = "Upss... ID no valido " . $id;
            }
            if (is_string($_POST['nombre'])) {
                $nombre = $_POST['nombre'];
                $mensaje .= "  Nombre: " . $nombre . "<br>";
            } else {
                $mensaje = "Upss... Nombre no valido " . $nombre;
            }
            if (is_string($_POST['sector'])) {
                $sector = $_POST['sector'];
                $mensaje .= "  sector: " . $sector . "<br>";
            } else {
                $mensaje = "Upss... sector no valido " . $sector;
            }

            if ($_POST['codigo']) {
                $codigo = $_POST['codigo'];
                $mensaje .= "  Código: " . $codigo . "<br>";
            } else {
                $mensaje = "Upss... Código no valido " . $codigo;
            }
            if (is_numeric($_POST['sacar'])) {
                $sacar = $_POST['sacar'];
                $mensaje .= "  Cantidad: " . $sacar . "<br>";
            } else {
                $mensaje = "Upss... Código no valido " . $sacar;
            }

            $stock = $_POST['stock'];

            if (!isset($_SESSION['CARRITO'])) {

                $herramienta = array(
                    'id' => $id,
                    'nombre' => $nombre,
                    'codigo' => $codigo,
                    'stock' => $stock,
                    'sacar' => $sacar,
                    'sector' => $sector
                );

                if ($herramienta['sacar'] <= 0) {
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?sacar=true');
                    return;
                }

                if ($herramienta['sacar'] > $herramienta['stock']) {
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?stock=true');
                    return;
                }

                $_SESSION['CARRITO'][0] = $herramienta;
                header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?ingresado=true');
            } else {

                $idHerramientas = array_column($_SESSION['CARRITO'], 'id');

                if (in_array($id, $idHerramientas)) {
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?repetido=true');
                    return;
                } else {
                    $numeroHerramientas = count($_SESSION['CARRITO']);
                    $herramienta = array(
                        'id' => $id,
                        'nombre' => $nombre,
                        'codigo' => $codigo,
                        'stock' => $stock,
                        'sacar' => $sacar,
                        'sector' => $sector
                    );

                    if ($herramienta['sacar'] <= 0) {
                        header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?sacar=true');
                        return;
                    }

                    if ($herramienta['sacar'] > $herramienta['stock']) {
                        header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?stock=true');
                        return;
                    }

                    array_push($_SESSION['CARRITO'], $herramienta);
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?ingresado=true');
                }
            }
            break;

            //RECUPERAR Y GUARDAR LA INFORMACIÓN DEL MATERIAL EN UN ARRAY 
        case 'AgregarMaterial':

            if (is_numeric($_POST['id'])) {
                $id = $_POST['id'];
                $mensaje .= "  ID: " . $id . "<br>";
            } else {
                $mensaje = "Upss... ID no valido " . $id;
            }
            
            if (is_string($_POST['sector'])) {
                $sector = $_POST['sector'];
                $mensaje .= "  sector: " . $sector . "<br>";
            } else {
                $mensaje = "Upss... sector no valido " . $sector;
            }
            if (is_string($_POST['nombre'])) {
                $nombre = $_POST['nombre'];
                $mensaje .= "  Nombre: " . $nombre . "<br>";
            } else {
                $mensaje = "Upss... Nombre no valido " . $nombre;
            }

            if (is_numeric($_POST['codigo'])) {
                $codigo = $_POST['codigo'];
                $mensaje .= "  Código: " . $codigo . "<br>";
            } else {
                $mensaje = "Upss... Código no valido " . $codigo;
            }
            if (is_numeric($_POST['sacar'])) {
                $sacar = $_POST['sacar'];
                $mensaje .= "  Cantidad: " . $sacar . "<br>";
            } else {
                $mensaje = "Upss... Código no valido " . $sacar;
            }

            $stock = $_POST['stock'];

            if (!isset($_SESSION['CARRITOMATERIAL'])) {

                $material = array(
                    'id' => $id,
                    'nombre' => $nombre,
                    'codigo' => $codigo,
                    'stock' => $stock,
                    'sacar' => $sacar,
                    'sector' => $sector
                );

                if ($material['sacar'] <= 0) {
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?sacar1=true');
                    return;
                }

                if ($material['sacar'] > $material['stock']) {
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?stock1=true');
                    return;
                }


                $_SESSION['CARRITOMATERIAL'][0] = $material;
                header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?ingresado1=true');
            } else {

                $idMaterial = array_column($_SESSION['CARRITOMATERIAL'], 'id');

                if (in_array($id, $idMaterial)) {
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?repetido1=true');
                    return;
                } else {
                    $numeroMateriales = count($_SESSION['CARRITOMATERIAL']);
                    $material = array(
                        'id' => $id,
                        'nombre' => $nombre,
                        'codigo' => $codigo,
                        'stock' => $stock,
                        'sacar' => $sacar,
                        'sector' => $sector
                    );

                    if ($material['sacar'] <= 0) {
                        header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?sacar1=true');
                        return;
                    }

                    if ($material['sacar'] > $material['stock']) {
                        header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?stock1=true');
                        return;
                    }

                    array_push($_SESSION['CARRITOMATERIAL'], $material);
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?ingresado1=true');
                }
            }
            break;


        case 'Eliminar':

            if (is_numeric($_POST['id'])) {

                $id = $_POST['id'];

                foreach ($_SESSION['CARRITO'] as $indice => $herramienta) {

                    if ($herramienta['id'] == $id) {
                        unset($_SESSION['CARRITO'][$indice]);
                        header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?eliminado=true');
                    }
                }
            }
            break;

        case 'EliminarMaterial':

            if (is_numeric($_POST['id'])) {

                $id = $_POST['id'];

                foreach ($_SESSION['CARRITOMATERIAL'] as $indice => $material) {

                    if ($material['id'] == $id) {
                        unset($_SESSION['CARRITOMATERIAL'][$indice]);
                        header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?eliminado1=true');
                    }
                }
            }
            break;


        case 'Guardar':

            if (!empty($_SESSION['CARRITOMATERIAL']) || !empty($_SESSION['CARRITO'])) {


                if (!empty($_POST['idDepartamento']) && !empty($_POST['idSolicitante'])) {

                    $idSolicitante = $_POST['idSolicitante'];
                    $idDepartamento = $_POST['idDepartamento'];
                    $estadoSolicitud = "Pendiente";
                    //$fecha = date('d/m/Y');
                 

                    $fecha  = date('Y-m-d');
                    $fechaLimite =  date("Y-m-d", strtotime($fecha . "+ 5 days"));

                    $queryInsertSoli = "INSERT solicitudes_arriendo_retiro (codSolicitud,  idSolicitante, idDepaSolicitante,estadoSolicitud, fechaTermino, tipoUsuario) 
                    VALUES('1', '${idSolicitante}', '${idDepartamento}', '${estadoSolicitud}','${fechaLimite}' ,'${tipoUsuario}')";

                    $resInsertSoli = mysqli_query($conexion, $queryInsertSoli);

                    // var_dump($queryInsertSoli);
                    // exit;

                    $idSoli = mysqli_insert_id($conexion);

                    if ($idSoli < 10) {
                        $solicitudReferente = "000" . $idSoli;
                    } else if ($idSoli > 9 && $idSoli < 100) {
                        $solicitudReferente = "00" . $idSoli;
                    } else if ($idSoli > 99 && $idSoli < 1000) {
                        $solicitudReferente = "0" . $idSoli;
                    } else if ($idSoli > 999) {
                        $solicitudReferente = $idSoli;
                    } else {
                        $solicitudReferente = $idSoli;
                    }

                    $numeroRes = "NS-REAR-" . $solicitudReferente;

                    $queryUpdateSoli = "UPDATE solicitudes_arriendo_retiro SET codSolicitud = '${numeroRes}' WHERE idSoli = ${idSoli}";
                    $resUpdateSoli = mysqli_query($conexion, $queryUpdateSoli);

                    if (!empty($_SESSION['CARRITOMATERIAL'])) {
                        foreach ($_SESSION['CARRITOMATERIAL'] as $indice => $material) {

                            $codigo = $material['codigo'];
                            $nombre = $material['nombre'];
                            $cantidad = $material['sacar'];
                            $stock = $material['stock'];
                            $idMaterial = $material['id'];
                            $sector = $material['sector'];

                            $resta = $stock - $cantidad;

                            $queryInsertMate = "INSERT INTO material_retirado(codMaterial, nomMaterial  ,cantidadMaterial,idSolicitud,idMaterial, sector)
                                VALUES ($codigo, '$nombre',$cantidad, $idSoli, $idMaterial, '$sector')";
                            $resInsertMate = mysqli_query($conexion, $queryInsertMate);

                            /**INSERT TABLA ESTADO DEl MATERIAL**/
                            $tipoActualizacion = "Retiro";
                     
                               //sumo 1 día
                                 $detalles = " 
                                 Datos del retiro del material:
                                     ->Retirado por: ${nomUsuario}
                                     ->Departamento Solicitante: ${nomDepartamento}
                                     ->Fecha Retiro: ${fecha}
                                     ->Cantidad: $cantidad                  
                                     
                                 ";
 
                             $sqlInsertHisMate = "INSERT INTO historial_estado_material(tipoActualizacion, cantidad,idMaterial, detalles)";
                             $sqlInsertHisMate .= "VALUES('${tipoActualizacion}', $cantidad, $idMaterial, '${detalles}')";

                            
                             $resSqlInsertHis = mysqli_query($conexion, $sqlInsertHisMate);

                            $queryUpdateMate = "UPDATE pañol_materiales SET cantMaterial = '${resta}' WHERE idMaterial = '${idMaterial}'";
                            $resUpdateMate = mysqli_query($conexion, $queryUpdateMate);

                            unset($_SESSION['CARRITOMATERIAL'][$indice]);
                        }
                    }

                    if (!empty($_SESSION['CARRITO'])) {
                        foreach ($_SESSION['CARRITO'] as $indice => $herramienta) {

                            $codigo = $herramienta['codigo'];
                            $nombre = $herramienta['nombre'];
                            $cantidad = $herramienta['sacar'];
                            $stock = $herramienta['stock'];
                            $idHerramienta = $herramienta['id'];
                            $sector = $herramienta['sector'];


                            $resta = $stock - $cantidad;

                            $queryInsert = "INSERT INTO herra_en_arriendo(codHerramienta, nomHerramienta  ,cantidadHerramienta,idSolicitud,idHerramienta, sector)
                                VALUES ('$codigo', '$nombre' ,$cantidad, $idSoli, $idHerramienta, '$sector')";
                            
                            //  var_dump($queryInsert);
                            //  exit;
                         
                            $resInsert = mysqli_query($conexion, $queryInsert);

                            /**INSERT TABLA ESTADO DE LA HERRAMIENTA**/
                            $tipoActualizacion = "Arriendo";
                     
                         
                              //sumo 1 día
                                $detalles = " 
                                Datos del arriendo de la herramienta:
                                    ->Arrendado por: ${nomUsuario}
                                    ->Departamento Solicitante: ${nomDepartamento}
                                    ->Fecha Solicitud: ${fecha}
                                    ->Fecha Limite Arriendo:  ${fechaLimite}                     
                                    
                                ";

                            $sqlInsertHis = "INSERT INTO historial_estado_herramienta(tipoActualizacion, cantidad,idHerramienta, detalles)";
                            $sqlInsertHis .= "VALUES('${tipoActualizacion}', $cantidad, $idHerramienta, '${detalles}')";
                            $resSqlInsertHis = mysqli_query($conexion, $sqlInsertHis);

                            $queryUpdate = "UPDATE pañol_herramientas SET cantHerramienta = '${resta}' WHERE idHerramienta = '${idHerramienta}'";
                            $resUpdate = mysqli_query($conexion, $queryUpdate);

                            unset($_SESSION['CARRITO'][$indice]);
                        }
                    }
                    if ($resInsertMate || $resInsert) {
                        header('Location: ../'.$ruta.'/listado-soli-ticket.php?guardado=true');
                    }
                } else {
                    header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?vaciopersona=true');
                }
            } else {
                header('Location: ../'.$ruta.'/soli-arriendo-retiro.php?vacios=true');
            }
            break;
    }
}
