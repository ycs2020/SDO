<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$rolUsuario = $_SESSION['rolUsuario'];

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
    $idTG = $_POST['idTG'];
    $ucaID = $_POST['ucaID'];
    $uca = $_POST['nombreEquipo'];
    $limpiezaGeneralEquipo = $_POST['limpiezaGeneralEquipo'];
    $verificarEstadoEquipo = $_POST['verificarEstadoEquipo'];
    $limpiezaContactos = $_POST['limpiezaContactos'];
    $verificacionEstadoPatchCord = $_POST['verificacionEstadoPatchCord'];
    $verificacionVoltaje = $_POST['verificacionVoltaje'];
    $verificacionIntegracion = $_POST['verificacionIntegracion'];
    $verificacionFuncionamiento = $_POST['verificacionFuncionamiento'];
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
    $opcionEstado = $_POST['estado'];
    $idUsuarioPost = $_POST['idUsuarioPost'];
    $idCategoria = 71;
    $idTareaP = $_POST['idTareaP'];
    $now2 = date('Y-m-d');


    $now = date('Y-m-d H:i:s');
    $obs = $_POST['obs'];
    $cantidadFilas = $_POST['cantidadFilas'];
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


    $sql = "INSERT INTO `datos_checklist_ucas`(`id`, `fecha`, `folio`, `uca`, `obs`, `limpiezaGeneralEquipo`, `verificarEstadoEquipo`, `limpiezaContactos`, `verificacionEstadoPatchCord`, `verificacionVoltaje`, `verificacionIntegracion`, `verificacionFuncionamiento`, `idTG`,cantidadPuertas, `idEquipamiento`) 
    VALUES (NULL,'$now','NULL','$uca','$obs',$limpiezaGeneralEquipo,$verificarEstadoEquipo,$limpiezaContactos,$verificacionEstadoPatchCord,$verificacionVoltaje,$verificacionIntegracion,$verificacionFuncionamiento,$idTG,$cantidadFilas,$ucaID)";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
            $idCheck = mysqli_insert_id($conexion);

            if ($idCheck < 10) {
                $idRecepcion2 = "000" . $idCheck;
            } else if ($idTG > 9 && $idCheck < 100) {
                $idRecepcion2 = "00" . $idCheck;
            } else if ($idTG > 99 && $idCheck < 1000) {
                $idRecepcion2 = "0" . $idCheck;
            } else if ($idTG > 999) {
                $idRecepcion2 = $idCheck;
            } else {
                $idRecepcion2 = $idCheck;
            }

            $folio = 'CHK-' . $idRecepcion2 . '-UCAS';

            $sqlUpdate = "UPDATE datos_checklist_ucas SET folio = '$folio' WHERE id = $idCheck";
            $resUpdate = mysqli_query($conexion, $sqlUpdate);


            $observaciones = "El cambio del estado del equipo esta detallado en el Check List - ".$folio;

            if(empty($ucaID)) {
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
                        VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $ucaID, $idCategoria,'$uca')";
                        $resInsert = mysqli_query($conexion, $sqlInsert);
                    }

                    //INSERTAMOS EN LA TABLA HISTORIAL DE CAMBIOS
                    $sqlInsert = "INSERT INTO historial_cambios_equipos (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $ucaID, $idCategoria,'$uca')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);

                    $sqlInsert = "INSERT INTO historial_cambios_equipos_respaldo (estado, observaciones , idUsuarioCambia, tipoUsuario, idEquipo, idCategoria,nombreEquipo) 
                    VALUES ('$opcionEstado', '$observaciones', $idUsuarioPost, '$rolUsuario2', $ucaID, $idCategoria,'$uca')";
                    $resInsert = mysqli_query($conexion, $sqlInsert);
                }
            }


            if ($cantidadFilas > 0) {
            $debugSql = ""; // Variable para acumular las consultas

            for ($r = 1; $r <= $cantidadFilas; $r++) {
                $numeroPuertaImagen = $_POST['numeroPuerta' . $r];
                $pmapuertaImagen = $_POST['pmapuerta' . $r];
                $hashImagen = $_POST['hashImagen'.$r];
                $tmp_name = $_FILES["imagenes".$r]["tmp_name"];
                $name = $_FILES["imagenes".$r]["name"];
                $valorImagen = $_POST['valorTermo'.$r];

                $query = "SELECT COUNT(*) FROM `archivos_uca_check_list` WHERE hashImagen = '$hashImagen'";
                $result = mysqli_query($conexion, $query);
                $row = mysqli_fetch_array($result);
                // Si el hash ya existe, redirigir al usuario
                if ($row[0] > 0) {
                    $eliminar = "DELETE FROM `datos_checklist_ucas` WHERE id = $idCheck";
                    $resEliminar = mysqli_query($conexion, $eliminar);

                    header('Location: ../' . $ruta . '/visualizardetalles.php?errorSubirImagenes1=true');
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
                            header('Location: ../' . $ruta . '/visualizardetalles.php?errorSubirImagenes2=true');
                            exit();
                        }
                    }
                }
            }

        }

        $sqlFuentes = "INSERT INTO `fuente_poder_uca`(`voltajeEntradaUCA`, `voltajeSalidaUCA`, `voltajeEntradaTablero`, `voltajeSalidaTablero`, `idChecList`) 
        VALUES ('$voltajeEntrada','$voltajeSalida','$voltajeEntradaTablero','$voltajeSalidaTablero',$idCheck)";
        $resFuentes = mysqli_query($conexion, $sqlFuentes);

        $sqlBaterias = "INSERT INTO `baterias_ucas`(`estadoBateriaUCA`, `SOH`, `SOC`, `A`, `V`, `R`, `idCheckList`) 
        VALUES ('$estadoBateriaUCA','$SOH','$SOC','$A','$V','$R',$idCheck)";
        $resBaterias = mysqli_query($conexion, $sqlBaterias);

        for($j = 1; $j <= 2; $j++){
            $estadoBateriaTablero1 = $_POST['estadoBateriaTablero'.$j];
            $SOHTablero1 = $_POST['SOHTablero'.$j];
            $SOCTablero1 = $_POST['SOCTablero'.$j];
            $ATablero1 = $_POST['ATablero'.$j];
            $VTablero1 = $_POST['VTablero'.$j];
            $RTablero1 = $_POST['RTablero'.$j];

            $sqlTablero = "INSERT INTO `baterias_tableros`(`estadoBateriaTablero`, `SOH`, `SOC`, `A`, `V`, `R`, `idCheckList`) 
            VALUES ('$estadoBateriaTablero1','$SOHTablero1','$SOCTablero1','$ATablero1','$VTablero1','$RTablero1',$idCheck)";
            $resTablero = mysqli_query($conexion, $sqlTablero);
        }


        if ($cantidadFilas > 0) {
            for ($i = 1; $i <= $cantidadFilas; $i++) {
                $pmapuerta = $_POST['pmapuerta' . $i];
                $limpiezaequiposbiometrico = $_POST['limpiezaequiposbiometrico' . $i];
                $lectortarjeta = $_POST['lectortarjeta' . $i];
                $lectorhuella = $_POST['lectorhuella' . $i];
                $lucesindicadorasbiometrico = $_POST['lucesindicadorasbiometrico' . $i];
                $limpiezaequiposmagnetico = $_POST['limpiezaequiposmagnetico' . $i];
                $lucesindicadorasmagnetico = $_POST['lucesindicadorasmagnetico' . $i];
                $limpiezaequiposbotonera = $_POST['limpiezaequiposbotonera' . $i];
                $botonesoperativos = $_POST['botonesoperativos' . $i];
                $numeroPuerta = $_POST['numeroPuerta' . $i];
                $estadoferroMagnetico = $_POST['estadoferroMagnetico' . $i];


                if ($limpiezaequiposbiometrico == '') {
                    $limpiezaequiposbiometrico = 0;
                }
                if ($lectortarjeta == '') {
                    $lectortarjeta = 0;
                }
                if ($lectorhuella == '') {
                    $lectorhuella = 0;
                }
                if ($lucesindicadorasbiometrico == '') {
                    $lucesindicadorasbiometrico = 0;
                }
                if ($limpiezaequiposmagnetico == '') {
                    $limpiezaequiposmagnetico = 0;
                }
                if ($lucesindicadorasmagnetico == '') {
                    $lucesindicadorasmagnetico = 0;
                }
                if ($limpiezaequiposbotonera == '') {
                    $limpiezaequiposbotonera = 0;
                }
                if ($botonesoperativos == '') {
                    $botonesoperativos = 0;
                }
                if($estadoferroMagnetico == ''){
                    $estadoferroMagnetico = 0;
                }


                $sqlPMA = "INSERT INTO `puertas_pma_checklist`(`id`, `puerta`, `pma`, `idCheck`) 
                VALUES (NULL,'$numeroPuerta','$pmapuerta',$idCheck)";
                $resPMA = mysqli_query($conexion, $sqlPMA);

                $sqlInsertBiometrico = "INSERT INTO `biometrico_checklist`(`id`, `puerta`, `limpiezaequiposbiometrico`, `lectortarjeta`, `lectorhuella`, `lucesindicadorasbiometrico`, `idCheck`) 
            VALUES (NULL,'$numeroPuerta',$limpiezaequiposbiometrico,$lectortarjeta,$lectorhuella,$lucesindicadorasbiometrico,$idCheck)";
                $resInsertBiometrico = mysqli_query($conexion, $sqlInsertBiometrico);

                $sqlInsertMagnetico = "INSERT INTO `magnetico_checklist`(`id`, `puerta`, `limpiezaequiposmagnetico`, `lucesindicadorasmagnetico`,estadoferroMagnetico, `idCheck`) 
            VALUES (NULL,'$numeroPuerta',$limpiezaequiposmagnetico,$lucesindicadorasmagnetico,$estadoferroMagnetico,$idCheck)";
                $resInsertMagnetico = mysqli_query($conexion, $sqlInsertMagnetico);

                $sqlInsertBotonera = "INSERT INTO `botonera_checklist`(`id`, `puerta`, `limpiezaequiposbotonera`, `botonesoperativos`, `idCheck`) 
            VALUES (NULL,'$numeroPuerta',$limpiezaequiposbotonera,$botonesoperativos,$idCheck)";
                $resInsertBotonera = mysqli_query($conexion, $sqlInsertBotonera);
            }
            if ($resInsertBiometrico && $resInsertMagnetico && $resInsertBotonera && $resPMA) {

                if(empty($idTareaP)){
                    header('Location: ../' . $ruta . '/visualizardetalles.php?exitoCheckListUcas=true');
                }else{
                    $queryAccion = "UPDATE tareas_carta_gantt SET inicioRealTareaP = '" . $now2 . "', porcentajeTareaP = 50, iniciadaTareaP = 1 WHERE idTareaP = " . $idTareaP;
                    $resAccion = mysqli_query($conexion,$queryAccion);
                    if($resAccion){
                        header('Location: ../'.$ruta.'/visualizardetalles.php?exitoCheckListUcasTarea=true');
                    }else{
                        header('Location: ../'.$ruta.'/visualizardetalles.php?errorCheckListUcasTarea=true');
                    }
                }
            } else {
                header('Location: ../' . $ruta . '/visualizardetalles.php?errorCheckListUcas=true');
            }
        }

    } else {
        header('Location: ../' . $ruta . '/visualizardetalles.php?errorCheckListUcas=true');
    }
} else {
    header('Location: ../' . $ruta . '/visualizardetalles.php?errorCheckListUcas=true');
}
