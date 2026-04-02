<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

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
    header('Location: ../../sdo-flujo/login_index.php?errorSesion=true');
}

if (!empty($_SESSION['CARRITOHERRARETIRO']) || !empty($_SESSION['CARRITOMATERETIRO'])) {

    $opcion = $_POST['opciones'];

    if ($opcion == '') {

        header('Location: ../../'. $ruta .'/arriendo-retiro.php?vaciosOpciones=true');
    }

    if (!empty($_POST['opciones'])) {

        if ($opcion == 'personal') {

            $idSolicitante = $_POST['personalTecnico'];
            $idDepartamento = $_POST['depaPersonal'];
            $tipoUsuario = 'personal';

            //Trae datos del solicitante para incluir en sección detalles
            $sqlPersonal = 'SELECT * FROM personal WHERE idPersonal = ' . $idSolicitante;
            $resPersonal = mysqli_query($conexion, $sqlPersonal);

            while ($rowPersonal = mysqli_fetch_assoc($resPersonal)) {

                $datosPersonal = [];

                $datosPersonal['nomPersonal'] = $rowPersonal['nomPersonal'];

                $nomSolicitante = $datosPersonal['nomPersonal'];
            }

            //Trae datos departamento solicitante
            $sqlDepa = 'SELECT * FROM departamentos WHERE idDepartamento  = ' . $idDepartamento;
            $resDepa = mysqli_query($conexion, $sqlDepa);

            while ($rowDepa = mysqli_fetch_assoc($resDepa)) {

                $datosDepa = [];

                $datosDepa['nomDepartamento'] = $rowDepa['nomDepartamento'];

                $nomDepartamento = $datosDepa['nomDepartamento'];
            }

        } else if ($opcion == 'jefes') {

            $idSolicitante = $_POST['nomTecnicoRecepcionador'];
            $idDepartamento = $_POST['departamentoSolicitante'];
            $tipoUsuario = 'jefes_tecnicos';

            //Trae datos del solicitante para incluir en sección detalles
            $sqlJefe = 'SELECT * FROM jefes_tecnicos WHERE idJefe  = ' . $idSolicitante;
            $resJefe = mysqli_query($conexion, $sqlJefe);

            while ($rowJefe = mysqli_fetch_assoc($resJefe)) {

                $datosJefe = [];

                $datosJefe['nomJefe'] = $rowJefe['nomJefe'];

                $nomSolicitante = $datosJefe['nomJefe'];
            }

            //Trae datos departamento solicitante
            $sqlDepa = 'SELECT * FROM departamentos WHERE idDepartamento  = ' . $idDepartamento;
            $resDepa = mysqli_query($conexion, $sqlDepa);

            while ($rowDepa = mysqli_fetch_assoc($resDepa)) {

                $datosDepa = [];

                $datosDepa['nomDepartamento'] = $rowDepa['nomDepartamento'];

                $nomDepartamento = $datosDepa['nomDepartamento'];
            }

        } else if ($opcion == 'directivos') {

            $idSolicitante = $_POST['directivo'];
            $idDepartamento = 1;
            $tipoUsuario = 'directivos';

            //Trae datos del solicitante para incluir en sección detalles
            $sqlDirectivo = 'SELECT * FROM directivos WHERE idDirectivo   = ' . $idSolicitante;
            $resDirectivo = mysqli_query($conexion, $sqlDirectivo);

            while ($rowDirectivo = mysqli_fetch_assoc($resDirectivo)) {

                $datosDirectivo = [];

                $datosDirectivo['nomDirectivo'] = $rowDirectivo['nomDirectivo'];

                $nomSolicitante = $datosDirectivo['nomDirectivo'];
            }
            
        } else {
            header('Location: ../../' . $ruta . '/arriendo-retiro.php?opcionNoValido=true');
        }

        $estadoSolicitud = "En Ejecucion";

        $fecha  = date('Y-m-d');
        $fechaLimite =  date("Y-m-d", strtotime($fecha . "+ 5 days"));

        $queryInsertSoli = "INSERT solicitudes_arriendo_retiro (codSolicitud,  idSolicitante, idDepaSolicitante,estadoSolicitud, fechaTermino, tipoUsuario) 
        VALUES('1', '${idSolicitante}', '${idDepartamento}', '${estadoSolicitud}','${fechaLimite}' ,'${tipoUsuario}')";

        $resInsertSoli = mysqli_query($conexion, $queryInsertSoli);

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

        if (!empty($_SESSION['CARRITOHERRARETIRO'])) {
            foreach ($_SESSION['CARRITOHERRARETIRO'] as $indice => $herramienta) {

                $idHerramienta = $herramienta['id'];
                $imagen = $herramienta['imagen'];
                $nomHerramienta = $herramienta['nomHerramienta'];
                $codHerramienta = $herramienta['codHerramienta'];
                $codInventario = $herramienta['codInventario'];
                $cantHerramienta = $herramienta['cantHerramienta'];
                $sacar = $herramienta['sacar'];

                $resta = $sacar - $cantHerramienta;

                //LIMPIAR VARIABLES STRING - ESCAPAR LAS COMILLAS PARA QUE SE HAN TRATADAS COMO STRING
                $nomHerramienta = str_replace("'", "\'", $nomHerramienta);

                $queryInsert = "INSERT INTO herra_en_arriendo(codHerramienta, nomHerramienta  ,cantidadHerramienta,idSolicitud,idHerramienta, fechaTermino)
                VALUES ('$codHerramienta', '$nomHerramienta' ,$cantHerramienta, $idSoli, $idHerramienta, '$fechaLimite')";

                $resInsert = mysqli_query($conexion, $queryInsert);

                /**INSERT TABLA ESTADO DE LA HERRAMIENTA**/
                $tipoActualizacion = "Arriendo";

                //sumo 1 día
                $detalles = " 
                Datos del arriendo de la herramienta:
                    ->Entregado Por : ${nomUsuario}
                    ->Departamento: ${rolUsuario}
                    ->Arrendado por: ${nomSolicitante}
                    ->Departamento Solicitante: ${nomDepartamento}
                    ->Fecha Solicitud: ${fecha}
                    ->Fecha Limite Arriendo:  ${fechaLimite}                     
                    
                ";

                $sqlInsertHis = "INSERT INTO historial_estado_herramienta(tipoActualizacion, cantidad,idHerramienta, detalles)";
                $sqlInsertHis .= "VALUES('${tipoActualizacion}', $cantHerramienta, $idHerramienta, '${detalles}')";
                $resSqlInsertHis = mysqli_query($conexion, $sqlInsertHis);

                $queryUpdate = "UPDATE pañol_herramientas SET cantHerramienta = '${resta}' WHERE idHerramienta = '${idHerramienta}'";
                $resUpdate = mysqli_query($conexion, $queryUpdate);

                unset($_SESSION['CARRITOHERRARETIRO'][$indice]);
            }
        }


        if (!empty($_SESSION['CARRITOMATERETIRO'])) {
            foreach ($_SESSION['CARRITOMATERETIRO'] as $indice => $material) {

                $idMaterial = $material['id'];
                $imagen = $material['imagen'];
                $nomMaterial = $material['nomMaterial'];
                $codMaterial = $material['codMaterial'];
                $codInventario = $material['codInventario'];
                $cantMaterial = $material['cantMaterial'];
                $sacar = $material['sacar'];

                //convierte las variables de string a integer(int - número entero)
                $cantMaterial = intval($cantMaterial);
                $sacar = intval($sacar);

                //LIMPIAR VARIABLES STRING - ESCAPAR LAS COMILLAS PARA QUE SE HAN TRATADAS COMO STRING
                $nomMaterial = str_replace("'", "\'", $nomMaterial);
           
                if($sacar > $cantMaterial){
                    header('Location: ../../' . $ruta .'/arriendo-retiro.php?noTiene=true');
                    return;
                }else{

                    echo 'no entre';
                }
        
                $resta = $cantMaterial - $sacar;

                $nomMaterial = preg_replace('([^A-Za-z0-9])', '', $nomMaterial);

                $queryInsertMate = "INSERT INTO material_retirado(codMaterial, nomMaterial  ,cantidadMaterial,idSolicitud,idMaterial)
                VALUES ('" . $codMaterial . "', '" . $nomMaterial . "', '" . $sacar ."','" . $idSoli ."' , '" . $idMaterial ."')";

                $resInsertMate = mysqli_query($conexion, $queryInsertMate);

                /**INSERT TABLA ESTADO DE LA HERRAMIENTA**/
                $tipoActualizacion = "Retiro";


                //sumo 1 día
                $detalles = " 
                Datos del retiro del material:
                    ->Entregado Por : ${nomUsuario}
                    ->Departamento: ${rolUsuario}
                    ->Retirado por: ${nomSolicitante}
                    ->Departamento Solicitante: ${nomDepartamento}
                    ->Fecha Solicitud: ${fecha}
                    ->Cantidad retirado: ${sacar}
                    
                ";

                $sqlInsertHisMate = "INSERT INTO historial_estado_material(tipoActualizacion, cantidad,idMaterial, detalles)";
                $sqlInsertHisMate .= "VALUES('${tipoActualizacion}', $cantMaterial, $idMaterial, '${detalles}')";
                $resSqlInsertHisMate = mysqli_query($conexion, $sqlInsertHisMate);


                $queryUpdateMate = "UPDATE pañol_materiales SET cantMaterial = '${resta}' WHERE idMaterial = '${idMaterial}'";
                $resUpdateMate = mysqli_query($conexion, $queryUpdateMate);

                unset($_SESSION['CARRITOMATERETIRO'][$indice]);
            }
        } else {
            echo 'error en carrito materiales';
        }

        if ($resInsertMate || $resInsert) {
            header('Location: ../../' . $ruta . '/listado-arriendo-retiro.php?guardado=true');
        }
    } else {
        header('Location: ../../' . $ruta . '/arriendo-retiro.php?errorVacios=true');
    }
} else {
    header('Location: ../../' . $ruta . '/arriendo-retiro.php?vacios=true');
}
