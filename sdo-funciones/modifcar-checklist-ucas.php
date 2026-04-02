<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$rolUsuario = $_SESSION['rolUsuario'];

$idUsuario = $_SESSION['idUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {

    $ruta = "sdo-directiva";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);

    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idCheck = $_POST['idCheck'];
    $idTG = $_POST['idTG'];
    $uca = $_POST['uca'];
    $ucaID = $_POST['ucaID'];
    $nombreEquipo = $_POST['nombreEquipo'];
    $limpiezaGeneralEquipo = $_POST['limpiezaGeneralEquipo'];
    $verificarEstadoEquipo = $_POST['verificarEstadoEquipo'];
    $limpiezaContactos = $_POST['limpiezaContactos'];
    $verificacionEstadoPatchCord = $_POST['verificacionEstadoPatchCord'];
    $verificacionVoltaje = $_POST['verificacionVoltaje'];
    $verificacionIntegracion = $_POST['verificacionIntegracion'];
    $verificacionFuncionamiento = $_POST['verificacionFuncionamiento'];
    $now = date('Y-m-d H:i:s');
    $obs = $_POST['obs'];
    $cantidadFilas = $_POST['cantidadFilas'];
    $folio = $_POST['folio'];
    $opcionEstado = $_POST['estado'];
    $idCategoria = 71;
    $idEquipamiento = $_POST['idEquipamiento'];
    $voltajeEntrada = $_POST['voltajeEntrada'];
    $voltajeSalida = $_POST['voltajeSalida'];
    $voltajeEntradaTablero = $_POST['voltajeEntradaTablero'];
    $voltajeSalidaTablero = $_POST['voltajeSalidaTablero'];
    $SOH = $_POST['SOH'];
    $SOC = $_POST['SOC'];
    $A = $_POST['A'];
    $V = $_POST['V'];
    $R = $_POST['R'];
    $estadoBateriaUCA = $_POST['estadoBateriaUCA'];
    $estadoCambia = $_POST['estadoCambia'];

    // Verificar y asignar valor 0 si la variable está vacía
    if ($limpiezaGeneralEquipo == '') {
        $limpiezaGeneralEquipo = 0;
    }

    if ($verificarEstadoEquipo == '') {
        $verificarEstadoEquipo = 0;
    }

    if ($limpiezaContactos == '') {
        $limpiezaContactos = 0;
    }

    if ($verificacionEstadoPatchCord == '') {
        $verificacionEstadoPatchCord = 0;
    }

    if ($verificacionVoltaje == '') {
        $verificacionVoltaje = 0;
    }

    if ($verificacionIntegracion == '') {
        $verificacionIntegracion = 0;
    }

    if ($verificacionFuncionamiento == '') {
        $verificacionFuncionamiento = 0;
    }

    if($nombreEquipo == '' || empty($nombreEquipo)){
        $sql = "UPDATE datos_checklist_ucas SET uca = '$uca',obs = '$obs',limpiezaGeneralEquipo = $limpiezaGeneralEquipo,verificarEstadoEquipo = $verificarEstadoEquipo,limpiezaContactos = $limpiezaContactos,verificacionEstadoPatchCord = $verificacionEstadoPatchCord,verificacionVoltaje = $verificacionVoltaje, verificacionIntegracion = $verificacionIntegracion,verificacionFuncionamiento = $verificacionFuncionamiento,cantidadPuertas = $cantidadFilas WHERE id = $idCheck";
    }else{
        $sql = "UPDATE datos_checklist_ucas SET uca = '$nombreEquipo',obs = '$obs',limpiezaGeneralEquipo = $limpiezaGeneralEquipo,verificarEstadoEquipo = $verificarEstadoEquipo,limpiezaContactos = $limpiezaContactos,verificacionEstadoPatchCord = $verificacionEstadoPatchCord,verificacionVoltaje = $verificacionVoltaje, verificacionIntegracion = $verificacionIntegracion,verificacionFuncionamiento = $verificacionFuncionamiento,cantidadPuertas = $cantidadFilas,idEquipamiento = $ucaID WHERE id = $idCheck";
    }
    $res = mysqli_query($conexion,$sql);
    if($res){

        if($estadoCambia == '' || empty($estadoCambia)){

        }else{
            $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;

            if(empty($vavID)){
                $sqlUpdateEquipo = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $idEquipamiento";
                $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
                if($resUpdateEquipo){
                    $existeRegistroObservacion = compruebaRegistroObservacion($idEquipamiento);

                    if($existeRegistroObservacion == "Si"){
                        $sqlElimina = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $idEquipamiento";
                        $resElimina = mysqli_query($conexion, $sqlElimina);
                    }

                    $existeRegistroFalla = compruebaRegistroFalla($idEquipamiento);
                    if($existeRegistroFalla == "Si"){
                        $sqlElimina = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $idEquipamiento";
                        $resElimina = mysqli_query($conexion, $sqlElimina);
                    }
                    if($opcionEstado == 'Fuera de Servicio'){
                        $sqlInsert = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nomEquipo) 
                        VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipamiento, $idCategoria,'$uca')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);
                    }

                    //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                    $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipamiento, $idCategoria,'$uca')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);

                    $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipamiento, $idCategoria,'$uca')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }
            }else{
                $sqlUpdateEquipo = "UPDATE equipamiento_hbqp SET estadoEquipo = '$opcionEstado' WHERE id = $ucaID";
                $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);
                if($resUpdateEquipo){
                    $existeRegistroObservacion = compruebaRegistroObservacion($ucaID);

                    if($existeRegistroObservacion == "Si"){
                        $sqlElimina = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $ucaID";
                        $resElimina = mysqli_query($conexion, $sqlElimina);
                    }

                    $existeRegistroFalla = compruebaRegistroFalla($ucaID);
                    if($existeRegistroFalla == "Si"){
                        $sqlElimina = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $ucaID";
                        $resElimina = mysqli_query($conexion, $sqlElimina);
                    }
                    if($opcionEstado == 'Fuera de Servicio'){
                        $sqlInsert = "INSERT INTO historial_cambios_equipos_falla (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nomEquipo) 
                        VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $ucaID, $idCategoria,'$nombreEquipo')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);
                    }
                    //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                    $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $ucaID, $idCategoria,'$nombreEquipo')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);

                    $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $ucaID, $idCategoria,'$nombreEquipo')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }
            }
        }

        $sqlUpdateFuente = "UPDATE `fuente_poder_uca` SET `voltajeEntradaUCA`='$voltajeEntrada',`voltajeSalidaUCA`='$voltajeSalida',`voltajeEntradaTablero`='$voltajeEntradaTablero',`voltajeSalidaTablero`='$voltajeSalidaTablero' WHERE WHERE idChecList = $idCheck";
        $resUpdateFuente = mysqli_query($conexion,$sqlUpdateFuente);

        $sqlUpdateBateriaUCA = "UPDATE `baterias_ucas` SET `estadoBateriaUCA`='$estadoBateriaUCA',`SOH`='$SOH',`SOC`='$SOC',`A`='$A',`V`='$V',`R`='$R' WHERE idCheckList = $idCheck";
        $resUpdateBateriaUCA = mysqli_query($conexion,$sqlUpdateBateriaUCA);

        for($j = 1; $j <= 2; $j++){
            $estadoBateriaTablero = $_POST['estadoBateriaTablero'.$j];
            $SOHTablero = $_POST['SOHTablero'.$j];
            $SOCtablero = $_POST['SOCTablero'.$j];
            $Atablero = $_POST['ATablero'.$j];
            $Vtablero = $_POST['VTablero'.$j];
            $Rtablero = $_POST['RTablero'.$j];
            $idBateriasTablero = $_POST['idBateriasTablero'.$j];

            $sqlBateriasTableros = "UPDATE `baterias_tableros` SET 
        `estadoBateriaTablero`='$estadoBateriaTablero',
        `SOH`='$SOHTablero',
        `SOC`='$SOCtablero',
        `A`='$Atablero',
        `V`='$Vtablero',
        `R`='$Rtablero' 
        WHERE idCheckList = $idCheck AND id = $idBateriasTablero";

        $resBateriasTablero = mysqli_query($conexion,$sqlBateriasTableros);

            $consultas[] = $sql;
        }





        for($i = 1; $i <= $cantidadFilas; $i++){
            $pmapuerta = $_POST['pmapuerta'.$i];
            $limpiezaequiposbiometrico = $_POST['limpiezaequiposbiometrico'.$i];
            $lectortarjeta = $_POST['lectortarjeta'.$i];
            $lectorhuella = $_POST['lectorhuella'.$i];
            $lucesindicadorasbiometrico = $_POST['lucesindicadorasbiometrico'.$i];
            $limpiezaequiposmagnetico = $_POST['limpiezaequiposmagnetico'.$i];
            $lucesindicadorasmagnetico = $_POST['lucesindicadorasmagnetico'.$i];
            $limpiezaequiposbotonera = $_POST['limpiezaequiposbotonera'.$i];
            $botonesoperativos = $_POST['botonesoperativos'.$i];
            $numeroPuerta = $_POST['numeroPuerta'.$i];
            $estadoferroMagnetico = $_POST['estadoferromagnetico' . $i];

            if($limpiezaequiposbiometrico == ''){
                $limpiezaequiposbiometrico = 0;
            }
            if($lectortarjeta == ''){
                $lectortarjeta = 0;
            }
            if($lectorhuella == ''){
                $lectorhuella = 0;
            }
            if($lucesindicadorasbiometrico == ''){
                $lucesindicadorasbiometrico = 0;
            }
            if($limpiezaequiposmagnetico == ''){
                $limpiezaequiposmagnetico = 0;
            }
            if($lucesindicadorasmagnetico == ''){
                $lucesindicadorasmagnetico = 0;
            }
            if($limpiezaequiposbotonera == ''){
                $limpiezaequiposbotonera = 0;
            }
            if($botonesoperativos == ''){
                $botonesoperativos = 0;
            }
            if($estadoferroMagnetico == ''){
                $estadoferroMagnetico = 0;
            }

            $puerta = 'Puerta '.$i;
            $buscar = buscarpma($puerta,$idCheck);
            if($buscar > 0){
                $sqlUpdateMagnetico = "UPDATE `magnetico_checklist` SET `estadoferroMagnetico` = $estadoferroMagnetico,`limpiezaequiposmagnetico`= $limpiezaequiposmagnetico,`lucesindicadorasmagnetico`= $lucesindicadorasmagnetico WHERE puerta = '$puerta' AND idCheck = $idCheck";
                $resUpdateMagnetico = mysqli_query($conexion,$sqlUpdateMagnetico);

                $sqlUpdateBiometrico = "UPDATE biometrico_checklist SET limpiezaequiposbiometrico = $limpiezaequiposbiometrico,lectortarjeta = $lectortarjeta,lectorhuella = $lectorhuella,lucesindicadorasbiometrico = $lucesindicadorasbiometrico WHERE puerta = '$puerta' AND idCheck = $idCheck";
                $resUpdateBiometrico = mysqli_query($conexion,$sqlUpdateBiometrico);

                $sqlUpdateBotonera = "UPDATE botonera_checklist SET limpiezaequiposbotonera = $limpiezaequiposbotonera,botonesoperativos = $botonesoperativos WHERE puerta = '$puerta' AND idCheck = $idCheck";
                $resUpdateBotonera = mysqli_query($conexion,$sqlUpdateBotonera);

                $sqlUpdatePMA = "UPDATE puertas_pma_checklist SET pma = '$pmapuerta' WHERE puerta = '$puerta' AND idCheck = $idCheck";
                $resUpdatePMA = mysqli_query($conexion,$sqlUpdatePMA);

            }else{

                $numeroPuertaImagen = $_POST['numeroPuerta' . $i];
                $pmapuertaImagen = $_POST['pmapuerta' . $i];
                $hashImagen = $_POST['hashImagen'.$i];
                $tmp_name = $_FILES["imagenes".$i]["tmp_name"];
                $name = $_FILES["imagenes".$i]["name"];
                echo $hashImagen.'<br>';
                echo $name;
                $valorImagen = $_POST['valorTermo'.$i];

                $query = "SELECT COUNT(*) FROM `archivos_uca_check_list` WHERE hashImagen = '$hashImagen'";
                $result = mysqli_query($conexion, $query);
                $row = mysqli_fetch_array($result);
                // Si el hash ya existe, redirigir al usuario
                if ($row[0] > 0) {
                    header('Location: ../' . $ruta . '/visualizardetalles.php?errorSubirImagenes33=true');
                    exit();
                }else{
                    $directorio = "../sdo-ucas-check/carpeta-" . $idCheck . "/PMA-" . $pmapuertaImagen . "/";

                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);  // Crear directorio si no existe
                    }
                    $dir = opendir($directorio);

                    $rutaArchivo = $directorio . $name;

                    $separarNombre = explode(".", $name);

                    $extImagen = end($separarNombre);

                    $extImagen = mb_strtolower($extImagen);

                    if (move_uploaded_file($tmp_name, $rutaArchivo)) {
                        chmod($rutaArchivo, 0777);
                        $sqlInsert = "INSERT INTO `archivos_uca_check_list`(`nomArchivo`, `rutaArchivo`, `tipoArchivo`, `hashImagen`,pmapuerta,puerta,valorImagen, `idCheckList`) 
                            VALUES ('$name','$rutaArchivo','$extImagen','$hashImagen','$pmapuertaImagen','$numeroPuertaImagen','$valorImagen',$idCheck)";

                        $debugSql .= $sqlInsert . "\n"; // Acumular las consultas

                        $resInsert = mysqli_query($conexion, $sqlInsert);
                        if(!$resInsert){
                            header('Location: ../' . $ruta . '/visualizardetalles.php?errorSubirImagenes=true');
                            exit();
                        }
                    }else{
                        echo 'error al subir imagen al servidor';
                        exit;
                    }
                }

                $sqlPMA = "INSERT INTO `puertas_pma_checklist`(`id`, `puerta`, `pma`, `idCheck`) 
                VALUES (NULL,'$numeroPuerta','$pmapuerta',$idCheck)";
                $resPMA = mysqli_query($conexion,$sqlPMA);

                $sqlInsertBiometrico = "INSERT INTO `biometrico_checklist`(`id`, `puerta`, `limpiezaequiposbiometrico`, `lectortarjeta`, `lectorhuella`, `lucesindicadorasbiometrico`, `idCheck`) 
            VALUES (NULL,'$numeroPuerta',$limpiezaequiposbiometrico,$lectortarjeta,$lectorhuella,$lucesindicadorasbiometrico,$idCheck)";
                $resInsertBiometrico = mysqli_query($conexion,$sqlInsertBiometrico);

                $sqlInsertMagnetico = "INSERT INTO `magnetico_checklist`(`id`, `puerta`, `limpiezaequiposmagnetico`, `lucesindicadorasmagnetico`,estadoferroMagnetico, `idCheck`) 
            VALUES (NULL,'$numeroPuerta',$limpiezaequiposmagnetico,$lucesindicadorasmagnetico,$estadoferroMagnetico,$idCheck)";
                $resInsertMagnetico = mysqli_query($conexion,$sqlInsertMagnetico);

                $sqlInsertBotonera = "INSERT INTO `botonera_checklist`(`id`, `puerta`, `limpiezaequiposbotonera`, `botonesoperativos`, `idCheck`) 
            VALUES (NULL,'$numeroPuerta',$limpiezaequiposbotonera,$botonesoperativos,$idCheck)";
                $resInsertBotonera = mysqli_query($conexion,$sqlInsertBotonera);

            }
        }
        header('Location: ../'.$ruta.'/visualizardetalles.php?exitoActualizarCheckListUCAS=true');
    }else{
        header('Location: ../'.$ruta.'/visualizardetalles.php?errorActualizarCheckListUCAS=true');
    }
}