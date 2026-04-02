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

if(isset($_POST['turno'])) {
    $id = $_POST['idReporte'];
    $turno = $_POST['turno'];
    $nomFuncionariosTurno = $_POST['nomFuncionariosTurno'];
    $tipoTurnoOriginal = $_POST['tipoTurnoOriginal'];
    $obsTareasAdicionales = $_POST['obsTareasAdicionales'];
    $now = date('Y-m-d');
    $fecha = $_POST['fecha'];

    if (is_array($nomFuncionariosTurno)) {
        sort($nomFuncionariosTurno);
        // Unir los elementos del array con ' - ' como delimitador
        $nomFuncionariosTurno = implode(' - ', $nomFuncionariosTurno);
    } else {
        $nomFuncionariosTurno = '';
    }
    $nomFuncionariosTurno = trim($nomFuncionariosTurno);

    if(empty($nomFuncionariosTurno)) {
        header('Location: ../'.$rutaDireccion.'/reportes_electricos.php?errorModificar=true');
    }else{
        //$sqlUpdate = "UPDATE `reportes_electricos_sdo` SET `turno`='$nomFuncionariosTurno',`tipoTurno`='$turno' WHERE id = $id";
        $sqlUpdate = "UPDATE `reportes_electricos_sdo` SET fecha = '$fecha',`turno`='$nomFuncionariosTurno' WHERE id = $id";
        //echo $sqlUpdate;
        $resUpdate = mysqli_query($conexion, $sqlUpdate);
        if ($resUpdate) {
            $todoOk = true;

            for ($i = 1; $i <= 4; $i++) {
                // Escapar solo los campos de texto (observaciones)
                $obsMT = empty($_POST['obsMT' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsMT' . $i]);
                $obsMTHora = $_POST['obsMTHora' . $i];
                $idMT = $_POST['idMT' . $i];

                $obsST = empty($_POST['obsST' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsST' . $i]);
                $obsSTHora = $_POST['obsSTHora' . $i];
                $idST = $_POST['idST' . $i];

                $obsSalaTrans = empty($_POST['obsSalaTrans' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaTrans' . $i]);
                $obsSalaTransHora = $_POST['obsSalaTransHora' . $i];
                $idSalaTrans = $_POST['idSalaTrans' . $i];

                $obsSalaTGBT1 = empty($_POST['obsSalaTGBT1_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaTGBT1_' . $i]);
                $obsSalaTGBTHORA = $_POST['obsSalaTGBTHORA1_' . $i];
                $idSalaTGBT1 = $_POST['idSalaTGBT1_' . $i];

                $obsSalaTGBT2 = empty($_POST['obsSalaTGBT2_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaTGBT2_' . $i]);
                $obsSalaTGBTHORA2 = $_POST['obsSalaTGBTHORA2_' . $i];
                $idSalaTGBT2 = $_POST['idSalaTGBT2_' . $i];

                $obsSalaMT2 = empty($_POST['obsSalaMT2_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsSalaMT2_' . $i]);
                $obsSalaMTHORA2 = $_POST['obsSalaMTHORA2_' . $i];
                $idSalaMT2 = $_POST['idSalaMT2_' . $i];

                $obsRecintosClinicos = empty($_POST['obsRecintosClinicos_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsRecintosClinicos_' . $i]);
                $obsRecintosClinicosHora = $_POST['obsRecintosClinicosHora_' . $i];
                $idRecintosClinicos = $_POST['idRecintosClinicos_' . $i];

                $obsRondasExterior = empty($_POST['obsRondasExterior_' . $i]) ? '-' : mysqli_real_escape_string($conexion, $_POST['obsRondasExterior_' . $i]);
                $obsRondasExteriorHora = $_POST['obsRondasExteriorHora_' . $i];
                $idRondasExterior = $_POST['idRondasExterior_' . $i];

                // Array de consultas
                $queries = [
                    "UPDATE observaciones_sala_mt SET hora = '$obsMTHora', obs = '$obsMT' WHERE id = '$idMT'",
                    "UPDATE observaciones_sala_transfo SET hora = '$obsSTHora', obs = '$obsST' WHERE id = '$idST'",
                    "UPDATE observaciones_sala_transferencia SET hora = '$obsSalaTransHora', obs = '$obsSalaTrans' WHERE id = '$idSalaTrans'",
                    "UPDATE observaciones_sala_tgbt_1 SET hora = '$obsSalaTGBTHORA', obs = '$obsSalaTGBT1' WHERE id = '$idSalaTGBT1'",
                    "UPDATE observaciones_sala_tgbt_2 SET hora = '$obsSalaTGBTHORA2', obs = '$obsSalaTGBT2' WHERE id = '$idSalaTGBT2'",
                    "UPDATE observaciones_sala_mt_2 SET hora = '$obsSalaMTHORA2', obs = '$obsSalaMT2' WHERE id = '$idSalaMT2'",
                    "UPDATE observaciones_recintos_clinicos SET hora = '$obsRecintosClinicosHora', obs = '$obsRecintosClinicos' WHERE id = '$idRecintosClinicos'",
                    "UPDATE observaciones_rondas_exterior SET hora = '$obsRondasExteriorHora', obs = '$obsRondasExterior' WHERE id = '$idRondasExterior'",
                ];

                // Ejecutar todas las consultas y mostrar resultado
                foreach ($queries as $query) {
                    $resultado = mysqli_query($conexion, $query);
                    if (!$resultado) {
                        echo "<strong>Error:</strong> " . mysqli_error($conexion) . "<br><br>";
                        $todoOk = false;
                    }
                }
            }

            if($todoOk){
                    $todoOk2 = true;
                    $sqlTareasAdicionales = "UPDATE `tareas_adicionales` SET `obs`='$obsTareasAdicionales',`tipoTurno`='$turno' WHERE idReporte = '$id'";
                    $resTareasAdicionales = mysqli_query($conexion, $sqlTareasAdicionales);

                    foreach ($_POST as $key6y7 => $value6y7) {
                        if (strpos($key6y7, 'temperaturaSalaTL6y7') === 0) {
                            preg_match('/temperaturaSalaTL6y7(\d+)_(\d+)_(\d+)/', $key6y7, $matches6y7);
                            if ($matches6y7) {
                                $trafo6y7 = $matches6y7[1]; // T1, T2, etc.
                                $linea6y7 = $matches6y7[2]; // TL1, TL2, etc.
                                $contador6y7 = $matches6y7[3]; // 1, 2, 3, etc.

                                $temperatura6y7 = mysqli_real_escape_string($conexion, $value6y7);
                                $idSalaT6y7 = $_POST["idTL6y7{$trafo6y7}_{$linea6y7}_{$contador6y7}"];
                                $hora6y7 = $_POST["horaTL6y7{$trafo6y7}_{$linea6y7}_{$contador6y7}"];

                                // Si la temperatura está vacía, asignar un valor predeterminado '-'
                                if ($temperatura6y7 == '' || empty($temperatura6y7)) {
                                    $temperatura6y7 = '-';
                                }

                                if ($turno === 'NOCHE') {
                                    // Si la hora está en turno diurno (09:00, 12:00, 15:00, 18:00), la cambiamos a la hora nocturna
                                    if ($hora6y7 === '9:00') {
                                        $hora6y7 = '21:00:00';
                                    } elseif ($hora6y7 === '12:00') {
                                        $hora6y7 = '00:00:00';
                                    } elseif ($hora6y7 === '15:00') {
                                        $hora6y7 = '03:00:00';
                                    } elseif ($hora6y7 === '18:00') {
                                        $hora6y7 = '06:00:00';
                                    }
                                } else if ($turno === 'DIURNO') {
                                    // Si la hora está en turno nocturno (21:00, 00:00, 03:00, 06:00), la cambiamos a la hora diurna
                                    if ($hora6y7 === '21:00') {
                                        $hora6y7 = '09:00:00';
                                    } elseif ($hora6y7 === '00:00') {
                                        $hora6y7 = '12:00:00';
                                    } elseif ($hora6y7 === '03:00') {
                                        $hora6y7 = '15:00:00';
                                    } elseif ($hora6y7 === '06:00') {
                                        $hora6y7 = '18:00:00';
                                    }
                                }

                                $sqlUpdateSalaT6y7 = "UPDATE temperaturas_transformadores SET hora='$hora6y7', temperatura='$temperatura6y7' WHERE id = $idSalaT6y7";
                                $resUpdateSalaT6y7 = mysqli_query($conexion, $sqlUpdateSalaT6y7);

                            }

                        }
                    }

                    foreach ($_POST as $key => $value) {
                        // Muestra todas las claves (keys) de $_POST
                       // echo "Clave: $key<br><br>";

                        // Verificar si la clave comienza con 'temperaturaSalaTL'
                        if (strpos($key, 'temperaturaSalaTL') === 0) {
                            // Extraer la información del nombre de cada input
                            preg_match('/temperaturaSalaTL(\d+)_(\d+)_(\d+)/', $key, $matches);

                            if ($matches) {
                                $trafo = $matches[1]; // T1, T2, etc.
                                $linea = $matches[2]; // TL1, TL2, etc.
                                $contador = $matches[3]; // 1, 2, 3, etc.

                                // Obtener la temperatura y el id
                                $temperatura = mysqli_real_escape_string($conexion, $value);
                                $idSalaT = $_POST["idTL{$trafo}_{$linea}_{$contador}"];
                                $hora = $_POST["horaTL{$trafo}_{$linea}_{$contador}"];

                                // Si la temperatura está vacía, asignar un valor predeterminado '-'
                                if ($temperatura == '' || empty($temperatura)) {
                                    $temperatura = '-';
                                }

// Asegúrate de imprimir las variables para depurar y ver qué valores tiene hora y turno
                               // echo "Hora original: " . $hora . "<br>";
                               // echo "Turno: " . $turno . "<br>";

// Convertir horas dependiendo del turno (diurno o nocturno)
                                    if ($turno === 'NOCHE') {
                                    // Si la hora está en turno diurno (09:00, 12:00, 15:00, 18:00), la cambiamos a la hora nocturna
                                    if ($hora === '9:00') {
                                        $hora = '21:00:00';
                                    } elseif ($hora === '12:00') {
                                        $hora = '00:00:00';
                                    } elseif ($hora === '15:00') {
                                        $hora = '03:00:00';
                                    } elseif ($hora === '18:00') {
                                        $hora = '06:00:00';
                                    }
                                } else if ($turno === 'DIURNO') {
                                    // Si la hora está en turno nocturno (21:00, 00:00, 03:00, 06:00), la cambiamos a la hora diurna
                                    if ($hora6y7 === '21:00') {
                                        $hora6y7 = '09:00:00';
                                    } elseif ($hora6y7 === '00:00') {
                                        $hora6y7 = '12:00:00';
                                    } elseif ($hora6y7 === '03:00') {
                                        $hora6y7 = '15:00:00';
                                    } elseif ($hora6y7 === '06:00') {
                                        $hora6y7 = '18:00:00';
                                    }
                                }
                                // Verifica si la hora fue cambiada
                              //  echo "Hora convertida: " . $hora . "<br>";

                                // Realizar la actualización en la base de datos sin comprobación previa
                                $sqlUpdateSalaT = "UPDATE temperaturas_transformadores SET hora='$hora', temperatura='$temperatura' WHERE id = $idSalaT";
                                //echo $sqlUpdateSalaT;

                                if (mysqli_query($conexion, $sqlUpdateSalaT)) {
                                   // echo "Registro actualizado con éxito para T$trafo - TL$linea - Hora: $hora.<br>";
                                } else {
                                    //echo "Error al actualizar: " . mysqli_error($conexion) . "<br>";
                                }

                            }
                        } else {
                            // Si no es una clave válida de temperatura, puedes agregar un mensaje de error si lo deseas
                            //echo 'Clave no válida: ' . $key . '<br>';
                        }
                    }


                    //UPS 1
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, 'temp_') === 0) {
                            $index = str_replace('temp_', '', $key);

                            // Claves relacionadas
                            $idKey = "idUps1_$index";
                            $horaKey = "upsHora_$index";
                            $alarmaKey = "alarmaSala_$index";
                            $eventoKey = "eventoSala_$index";

                            // Recolección de datos
                            $idUPS = $_POST[$idKey] ?? null;
                            $hora = trim($_POST[$horaKey] ?? '');
                            $temperatura = mysqli_real_escape_string($conexion, trim($value));
                            $alarma = mysqli_real_escape_string($conexion, trim($_POST[$alarmaKey] ?? ''));
                            $evento = mysqli_real_escape_string($conexion, trim($_POST[$eventoKey] ?? ''));

                            // Validación
                            if (!is_numeric($idUPS)) continue;
                            if ($temperatura === '') $temperatura = '-';

                            // Conversión de hora según el turno
                            $conversionHoras = [
                                'DIURNO' => ['21:00' => '09:00', '00:00' => '12:00', '03:00' => '15:00', '06:00' => '18:00'],
                                'NOCHE'  => ['9:00' => '21:00', '12:00' => '00:00', '15:00' => '03:00', '18:00' => '06:00']
                            ];

                            if (isset($conversionHoras[$turno][$hora])) {
                                $hora = $conversionHoras[$turno][$hora];
                            }

                            // Query de actualización
                                                $sqlUpdateUPS = "
                                UPDATE `ups_registro` 
                                SET `hora` = '$hora', 
                                    `temperatura` = '$temperatura', 
                                    `alarma` = '$alarma', 
                                    `ultimo_evento` = '$evento' 
                                WHERE `id` = '$idUPS'
                            ";

                            //echo $sqlUpdateUPS . '<br><br>';
                            mysqli_query($conexion, $sqlUpdateUPS);
                        }
                    }



                    $upsNombres2 = ['DIN 2'];

                    // Normalizar keys (para match en POST)
                    $upsKeys2 = [];
                    foreach ($upsNombres2 as $ups2) {
                        $upsKeys2[str_replace([' ', '-'], '', $ups2)] = $ups2;
                    }

                    // Procesar POST
                    foreach ($_POST as $key2 => $value2) {
                        if (strpos($key2, 'tempDIN2_') === 0) {
                            $index2 = str_replace('tempDIN2_', '', $key2);

                            // Claves relacionadas
                            $idKey2 = "idUPSDIN2_$index2";
                            $horaKey2 = "upsHoraDIN2_$index2";
                            $alarmaKey2 = "alarmaDIN2_$index2";
                            $eventoKey2 = "eventoDIN2_$index2";

                            $idUPS2 = $_POST[$idKey2] ?? null;
                            $hora2 = trim($_POST[$horaKey2] ?? '');
                            $temperatura2 = mysqli_real_escape_string($conexion, trim($value2));
                            $alarma2 = mysqli_real_escape_string($conexion, trim($_POST[$alarmaKey2] ?? ''));
                            $evento2 = mysqli_real_escape_string($conexion, trim($_POST[$eventoKey2] ?? ''));

                            // Validar ID
                            if (!is_numeric($idUPS2)) continue;

                            // Normaliza temperatura vacía
                            if ($temperatura2 === '') {
                                $temperatura2 = '-';
                            }

                            // Conversión hora por turno
                            $conversionHoras = [
                                'DIURNO' => ['21:00' => '09:00', '00:00' => '12:00', '03:00' => '15:00', '06:00' => '18:00'],
                                'NOCHE'  => ['9:00' => '21:00', '12:00' => '00:00', '15:00' => '03:00', '18:00' => '06:00']
                            ];

                            if (isset($conversionHoras[$turno][$hora2])) {
                                $hora2 = $conversionHoras[$turno][$hora2];
                            }

                            // Ejecutar UPDATE
                            $sqlUpdateUPS2 = "
                                    UPDATE `ups_registro` 
                                    SET `hora` = '$hora2', 
                                        `temperatura` = '$temperatura2', 
                                        `alarma` = '$alarma2', 
                                        `ultimo_evento` = '$evento2' 
                                    WHERE `id` = '$idUPS2'
                                ";

                            //echo $sqlUpdateUPS2 . '<br><br>';
                             mysqli_query($conexion, $sqlUpdateUPS2);
                        }
                    }

                    ///GG
                    if (isset($_POST['modo']) && is_array($_POST['modo'])) {

                        // Mapear índices a horas usando los datos enviados en los encabezados
                        $horaGG = $_POST['horaGG'] ?? []; // Ej: [0 => "9:00", 1 => "12:00", ...]

                        foreach ($_POST['modo'] as $equipo => $horasModo) {
                            // Verificamos si hay observación
                            $observacion = mysqli_real_escape_string($conexion, trim($_POST['observaciones'][$equipo] ?? '-'));

                            foreach ($horasModo as $index => $modo) {
                                // Convertir el índice (0, 1, ...) a la hora correspondiente
                                $hora = $horaGG[$index] ?? '-';

                                // Sanitizar
                                $modo = empty($modo) ? '-' : mysqli_real_escape_string($conexion, trim($modo));
                                $hora = mysqli_real_escape_string($conexion, trim($hora));

                                // Verificar si existe el ID para este índice
                                if (isset($_POST['id'][$equipo][$index])) {
                                    $idGrupo = mysqli_real_escape_string($conexion, $_POST['id'][$equipo][$index]);

                                    $sqlGE = "UPDATE `grupos_electrogenos` 
                                      SET `hora` = '$hora', `modo` = '$modo', `observaciones` = '$observacion' 
                                      WHERE `id` = '$idGrupo'";

                                    // Ejecutar si lo necesitas:
                                     $resGE = mysqli_query($conexion, $sqlGE);
                                    // if (!$resGE) {
                                    //     echo "❌ Error MySQL: " . mysqli_error($conexion) . "<br>";
                                    // }

                                } else {
                                    echo "⚠️ ID no encontrado en \$_POST['id'][$equipo][$index]<br>";
                                }
                            }
                        }
                    } else {
                        echo "❌ Error: 'modo' no está definido o no es un array<br>";
                    }


                    //Rondas por los pabellones

                    if ($turno === 'NOCHE') {
                        for ($i = 1; $i <= 10; $i++) {
                            $pabellon = "$i";
                            $protecciones = mysqli_real_escape_string($conexion, $_POST["protecciones_tablero$i"] ?? '-');
                            $finder = mysqli_real_escape_string($conexion, $_POST["alarma_finder$i"] ?? '-');
                            $ups = mysqli_real_escape_string($conexion, $_POST["alarma_ups$i"] ?? '-');
                            $luces = mysqli_real_escape_string($conexion, $_POST["estado_luces$i"] ?? '-');
                            $puertas = mysqli_real_escape_string($conexion, $_POST["condicion_puertas$i"] ?? '-');
                            $otras = mysqli_real_escape_string($conexion, $_POST["otras_observaciones$i"] ?? '-');

                            // Verifica si ya existe un registro para este pabellón y reporte
                            $checkSQL = "SELECT id FROM pabellones_clinicos WHERE idReporte = '$id' AND pabellon = '$pabellon'";
                           // ECHO $checkSQL.'<br><br>';
                            $resCheck = mysqli_query($conexion, $checkSQL);

                            if (mysqli_num_rows($resCheck) > 0) {
                                // Si existe, actualiza
                                $sqlPabellon = "UPDATE pabellones_clinicos 
                            SET protecciones_tablero = '$protecciones',
                                alarma_finder = '$finder',
                                alarma_ups = '$ups',
                                estado_luces = '$luces',
                                condicion_puertas = '$puertas',
                                otras_observaciones = '$otras'
                            WHERE idReporte = '$id' AND pabellon = '$pabellon'";
                               //echo $sqlPabellon.'<br>';
                            }else {
                                // Si no existe, inserta
                                $sqlPabellon = "INSERT INTO `pabellones_clinicos`(`pabellon`, `protecciones_tablero`, `alarma_finder`, `alarma_ups`, `estado_luces`, `condicion_puertas`, `otras_observaciones`, `fecha`, `turno`, `idReporte`) 
                                VALUES ('$pabellon','$protecciones','$finder','$ups','$luces','$puertas','$otras','$now','$turno','$id')";
                            }
                            $resPabellon = mysqli_query($conexion, $sqlPabellon);
                            /*
                            if (!$resPabellon) {
                                echo "❌ Error al guardar Pabellón $i: " . mysqli_error($conexion) . "<br>";
                            }
                           */
                        }
                    }
                    header('Location: ../'.$rutaDireccion.'/reportes_electricos.php?exitoModificarReporteGeneral=true');
                }else{
                    header('Location: ../'.$rutaDireccion.'/reportes_electricos.php?errorModificarReporteGeneral=true');
                    exit();
                }
        }else{
            header('Location: ../'.$rutaDireccion.'/reportes_electricos.php?errorModificarReporteGeneral1=true');
            exit();
        }
    }

}else{
    header('Location: ../'.$rutaDireccion.'/reportes_electricos.php?errorModificarReporteGeneral2=true');
    exit();
}