<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

date_default_timezone_set('America/Santiago');

$now = date('Y-m-d H:i:s');

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if($rolUsuario2 == "oficina tecnica"){
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['turno'])){
    $turno = $_POST['turno'];
    $fecha = $_POST['fecha'];
    $nomFuncionariosTurno = $_POST['nomFuncionariosTurno'];
    $obsTareasAdicionales = $_POST['obsTareasAdicionales'];

    if($obsTareasAdicionales == '' || empty($obsTareasAdicionales)){
        $obsTareasAdicionales = 'Sin Tareas Adicionales';
    }
    $ano = date('Y');

    $folio = "";

    if (is_array($nomFuncionariosTurno)) {
        sort($nomFuncionariosTurno);
        // Unir los elementos del array con ' - ' como delimitador
        $nomFuncionariosTurno = implode(' - ', $nomFuncionariosTurno);
    } else {
        $nomFuncionariosTurno = '';
    }
    $nomFuncionariosTurno = trim($nomFuncionariosTurno);

    if(empty($nomFuncionariosTurno)){
        header('Location: ../sdo-personal/reportes_electricos.php?errorGenerar=true');
    }else{
        $sql = "INSERT INTO `reportes_electricos_sdo`(`folio`, `turno`, `tipoTurno`, `fecha`) 
        VALUES ('NULL','$nomFuncionariosTurno','$turno','$fecha')";
        $res = mysqli_query($conexion,$sql);
        if($res){
            $id = mysqli_insert_id($conexion);
            $folio = "ELEC-".$id."-".$ano;

            $sqlUpdate = "UPDATE `reportes_electricos_sdo` SET `folio`='$folio' WHERE `id` = $id";
            $resUpdate = mysqli_query($conexion,$sqlUpdate);

            $todoOk = true; // Bandera general

            for ($i = 1; $i <= 4; $i++) {
                $obsMT = empty($_POST['obsMT' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsMT' . $i]);
                $obsST = empty($_POST['obsST' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsST' . $i]);
                $obsSalaTrans = empty($_POST['obsSalaTrans' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaTrans' . $i]);
                $obsSalaTGBT1 = empty($_POST['obsSalaTGBT1_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaTGBT1_' . $i]);
                $obsSalaTGBT2 = empty($_POST['obsSalaTGBT2_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaTGBT2_' . $i]);
                $obsSalaMT2 = empty($_POST['obsSalaMT2_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaMT2_' . $i]);
                $obsRecintosClinicos = empty($_POST['obsRecintosClinicos_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsRecintosClinicos_' . $i]);
                $obsRondasExterior = empty($_POST['obsRondasExterior_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsRondasExterior_' . $i]);

                // Horas sin escape
                $obsMTHora = $_POST['obsMTHora' . $i];
                $obsSTHora = $_POST['obsSTHora' . $i];
                $obsSalaTransHora = $_POST['obsSalaTransHora' . $i];
                $obsSalaTGBTHORA = $_POST['obsSalaTGBTHORA1_' . $i];
                $obsSalaTGBTHORA2 = $_POST['obsSalaTGBTHORA2_' . $i];
                $obsSalaMTHORA2 = $_POST['obsSalaMTHORA2_' . $i];
                $obsRecintosClinicosHora = $_POST['obsRecintosClinicosHora_' . $i];
                $obsRondasExteriorHora = $_POST['obsRondasExteriorHora_' . $i];


                $todoOk = $todoOk &&
                    mysqli_query($conexion, "INSERT INTO observaciones_sala_mt (hora, obs, idReporte) VALUES ('$obsMTHora', '$obsMT', '$id')") &&
                    mysqli_query($conexion, "INSERT INTO observaciones_sala_transfo (hora, obs, idReporte) VALUES ('$obsSTHora', '$obsST', '$id')") &&
                    mysqli_query($conexion, "INSERT INTO observaciones_sala_transferencia (hora, obs, idReporte) VALUES ('$obsSalaTransHora', '$obsSalaTrans', '$id')") &&
                    mysqli_query($conexion, "INSERT INTO observaciones_sala_tgbt_1 (hora, obs, idReporte) VALUES ('$obsSalaTGBTHORA', '$obsSalaTGBT1', '$id')") &&
                    mysqli_query($conexion, "INSERT INTO observaciones_sala_tgbt_2 (hora, obs, idReporte) VALUES ('$obsSalaTGBTHORA2', '$obsSalaTGBT2', '$id')") &&
                    mysqli_query($conexion, "INSERT INTO observaciones_sala_mt_2 (hora, obs, idReporte) VALUES ('$obsSalaMTHORA2', '$obsSalaMT2', '$id')") &&
                    mysqli_query($conexion, "INSERT INTO observaciones_recintos_clinicos (hora, obs, idReporte) VALUES ('$obsRecintosClinicosHora', '$obsRecintosClinicos', '$id')") &&
                    mysqli_query($conexion, "INSERT INTO observaciones_rondas_exterior (hora, obs, idReporte) VALUES ('$obsRondasExteriorHora', '$obsRondasExterior', '$id')");
            }

            // Redireccionar si todo fue bien
            if($todoOk) {
                $todoOk_2 = true;

                $sqlTareasAdicionales = "INSERT INTO `tareas_adicionales`(`obs`, `tipoTurno`, `idReporte`) 
                VALUES ('$obsTareasAdicionales','$turno','$id')";

                $todoOk_2 = $todoOk_2 && mysqli_query($conexion, $sqlTareasAdicionales);


                $horasTransformadores = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'tranformadoresHora') === 0) {
                        $horasTransformadores[] = $value;
                    }
                }
                $horasTransformadores = array_unique($horasTransformadores);

                $horasUPS = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'upsHoras') === 0) {
                        $horasUPS[] = $value;
                    }
                }
                $horasUPS = array_unique($horasUPS); // Elimina duplicados

                $upsNames = ["SAI-EST", "SAI-EST 1", "SAI-EST 2", "DIN 1", "DIN IMA"];


                //GG
                $horasGG = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'salaGrupoElecHora') === 0) {
                        $horaGRUPOS = substr($key, strlen('salaGrupoElecHora'));
                        if (!in_array($horaGRUPOS, $horasGG)) {
                            $horasGG[] = $horaGRUPOS;
                        }
                    }
                }

                $equiposGG = ["GG1", "GG2", "GG3", "GG4"];



                $horasDIN2 = [];

                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'DIN2Hora') === 0) {
                        $horaDINN2_1 = substr($key, strlen('DIN2Hora'));
                        if (!in_array($horaDINN2_1, $horasDIN2)) {
                            $horasDIN2[] = $horaDINN2_1;
                        }
                    }
                }

                $horasTransformadores6y7 = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'tranformadoresHora6y7') === 0) {
                        $horasTransformadores6y7[] = $value;
                    }
                }
                $horasTransformadores6y7 = array_unique($horasTransformadores6y7);


                // Guardar datos de temperaturas de transformadores
                for ($trafo = 1; $trafo <= 5; $trafo++) {
                    for ($linea = 1; $linea <= 3; $linea++) {
                        foreach ($horasTransformadores as $index => $hora) {
                            $campo = "temperaturaSalaTL{$trafo}_{$linea}_" . ($index + 1);
                            $temperatura = isset($_POST[$campo]) && $_POST[$campo] !== '' ? $_POST[$campo] : "-";

                            $sqlInsert9 = "INSERT INTO `temperaturas_transformadores`(`trafo`, `linea`, `hora`, `temperatura`, `fecha`, `idReporte`, `turno`) 
                        VALUES ('$trafo','$linea','$hora','$temperatura','$fecha','$id','$turno')";

                            $todoOk_2 = $todoOk_2 && mysqli_query($conexion, $sqlInsert9);
                        }
                    }
                }

                foreach ($upsNames as $ups) {
                    $upsLimpiado = str_replace([' ', '-'], '', $ups); // Eliminar espacios y guiones
                    foreach ($horasUPS as $index => $hora) {
                        $i = $index + 1;
                        $sufijo = $upsLimpiado . "_" . $i;

                        $temp = isset($_POST["tempSala$sufijo"]) && $_POST["tempSala$sufijo"] !== '' ? $_POST["tempSala$sufijo"] : "-";
                        $alarma = isset($_POST["alarmaSala$sufijo"]) && $_POST["alarmaSala$sufijo"] !== '' ? $_POST["alarmaSala$sufijo"] : "Sin alarma";
                        $evento = isset($_POST["ultimoeventoSala$sufijo"]) && $_POST["ultimoeventoSala$sufijo"] !== '' ? $_POST["ultimoeventoSala$sufijo"] : "-";

                        $sqlInsert10 = "INSERT INTO ups_registro (ups, hora, temperatura, alarma, ultimo_evento, fecha, idReporte, turno)
                    VALUES ('$ups', '$hora', '$temp', '$alarma', '$evento', '$fecha', '$id', '$turno')";
                        //echo $sqlInsert10.'<br><br><br>';
                        $todoOk_2 = $todoOk_2 && mysqli_query($conexion, $sqlInsert10);
                    }
                }


                foreach ($equiposGG as $equipo) {
                    $observaciones = isset($_POST["observaciones$equipo"]) && $_POST["observaciones$equipo"] !== '' ? $_POST["observaciones$equipo"] : "-";

                    echo $observaciones.'<br><br>';
                    var_dump($observaciones);

                    foreach ($horasGG as $index => $horaGruposElectr) {
                        $i = $index + 1;
                        $campoModo = "modo{$equipo}_{$i}";
                        $modo = isset($_POST[$campoModo]) && $_POST[$campoModo] !== '' ? $_POST[$campoModo] : "-";

                        $sqlInsertGG = "INSERT INTO grupos_electrogenos (equipo, hora, modo, observaciones, fecha, idReporte, turno)
                        VALUES ('$equipo', '$horaGruposElectr', '$modo', '$observaciones', '$fecha', '$id', '$turno')";

                        // echo $sqlInsertGG . '<br>'; // Debug
                        $todoOk_2 = $todoOk_2 && mysqli_query($conexion, $sqlInsertGG);
                    }
                }


                foreach ($horasDIN2 as $index => $horaDIN2) {
                    $i = $index + 1;
                    $sufijo = "DIN2_" . $i;

                    $temp = isset($_POST["tempSala$sufijo"]) && $_POST["tempSala$sufijo"] !== '' ? $_POST["tempSala$sufijo"] : "-";
                    $alarma = isset($_POST["alarmaSala$sufijo"]) && $_POST["alarmaSala$sufijo"] !== '' ? $_POST["alarmaSala$sufijo"] : "Sin alarma";
                    $evento = isset($_POST["eventoSala$sufijo"]) && $_POST["eventoSala$sufijo"] !== '' ? $_POST["eventoSala$sufijo"] : "-";

                    $sqlDIN2 = "INSERT INTO ups_registro (ups, hora, temperatura, alarma, ultimo_evento, fecha, idReporte, turno)
                VALUES ('DIN 2', '$horaDIN2', '$temp', '$alarma', '$evento', '$fecha', '$id', '$turno')";

                    // echo $sqlDIN2 . "<br><br>"; // Debug si lo necesitas
                    $todoOk_2 = $todoOk_2 && mysqli_query($conexion, $sqlDIN2);
                }

                for ($trafo = 6; $trafo <= 7; $trafo++) {
                    for ($linea = 1; $linea <= 3; $linea++) {
                        foreach ($horasTransformadores6y7 as $index => $hora67) {
                            $campo = "temperaturaSalaTL{$trafo}_{$linea}_" . ($index + 1);
                            $temperatura = isset($_POST[$campo]) && $_POST[$campo] !== '' ? $_POST[$campo] : "-";

                            $sqlInsert13 = "INSERT INTO temperaturas_transformadores (
                            trafo, linea, hora, temperatura, fecha, idReporte, turno
                        ) VALUES (
                            '$trafo', '$linea', '$hora67', '$temperatura', '$fecha', '$id', '$turno'
                        )";

                            $todoOk_2 = $todoOk_2 && mysqli_query($conexion, $sqlInsert13);
                            // echo $sqlInsert . "<br>"; // Para debug
                        }
                    }
                }

                if ($turno === "NOCHE") {
                    for ($i = 1; $i <= 10; $i++) {
                        $protecciones = isset($_POST["proteccionesTablero_$i"]) && $_POST["proteccionesTablero_$i"] !== '' ? $_POST["proteccionesTablero_$i"] : "-";
                        $finder = isset($_POST["alarmaFinder_$i"]) && $_POST["alarmaFinder_$i"] !== '' ? $_POST["alarmaFinder_$i"] : "-";
                        $ups = isset($_POST["alarmaUPS_$i"]) && $_POST["alarmaUPS_$i"] !== '' ? $_POST["alarmaUPS_$i"] : "-";
                        $luces = isset($_POST["estadoLuces_$i"]) && $_POST["estadoLuces_$i"] !== '' ? $_POST["estadoLuces_$i"] : "-";
                        $puertas = isset($_POST["condicionPuertas_$i"]) && $_POST["condicionPuertas_$i"] !== '' ? $_POST["condicionPuertas_$i"] : "-";
                        $otras = isset($_POST["otrasObservaciones_$i"]) && $_POST["otrasObservaciones_$i"] !== '' ? $_POST["otrasObservaciones_$i"] : "-";

                        $sqlInsert14 = "INSERT INTO pabellones_clinicos (
                        pabellon, protecciones_tablero, alarma_finder, alarma_ups, estado_luces, 
                        condicion_puertas, otras_observaciones, fecha, turno, idReporte
                    ) VALUES (
                        '$i', '$protecciones', '$finder', '$ups', '$luces', '$puertas', '$otras', 
                        '$fecha', '$turno', '$id'
                    )";

                        $todoOk_2 = $todoOk_2 && mysqli_query($conexion, $sqlInsert14);
                        // echo $sqlInsert . "<br>"; // Para debug
                    }
                }
                // --- REDIRECCIÓN ---//
                if($todoOk_2) {
                    header('Location: ../sdo-personal/reportes_electricos.php?exitoGenerarReporte=true');
                    exit();
                } else {
                    header('Location: ../sdo-personal/reportes_electricos.php?errorGenerarTablasCompuestas=true');
                    exit();
                }

            } else {
                header('Location: ../sdo-personal/reportes_electricos.php?errorGenerarTablasSimples=true');
                exit();
            }

        }else{
            header('Location: ../sdo-personal/reportes_electricos.php?errorGenerarReporteGeneral1=true');
            exit();
        }
    }
}else{
    header('Location: ../sdo-personal/reportes_electricos.php?errorGenerarReporteGeneral=true');
    exit();
}