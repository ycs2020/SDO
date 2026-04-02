<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');

//echo $_POST['checklist'];
//exit();

//VARIABLES DE SESSION
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
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$campoUpdate = '';

    if (isset($_POST['tituloTareaP'],$_POST['turnoAsignado'],$_POST['funcionarioTareaOT'],$_POST['tipoTrabajo'],$_POST['actividadTareaP'], $_POST['inicioPlanTareaP'], $_POST['finPlanTareaP'], $_POST['idTareaP'], $_POST['nomCreadorTareaP'], $_POST['deptoCreadorTareaP'], $_POST['tipoDiasHabil'])) {

    $tituloTareaP = $_POST['tituloTareaP'];
    $turnoAsignado = $_POST['turnoAsignado'];
    $tipoTrabajo = $_POST['tipoTrabajo'];
    $funcionariosSeleccionados = $_POST['funcionarioTareaOT'] ?? []; // Array de funcionarios seleccionados

    if(empty($funcionariosSeleccionados)){
        header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
    }
    if($turnoAsignado == 1) {
        $turnoAsignado2 = 'Personal Diurno';
    }else if($turnoAsignado == 2) {
        $turnoAsignado2 = 'Personal Nocturno';
    }else{
        $turnoAsignado2 = '';
    }
    if(empty($tipoTrabajo) || $tipoTrabajo == 0){
        header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
    }

    $funcionarios = implode(" - ", $funcionariosSeleccionados); // Convertir array a string para guardar en la DB


    $actividadTarea = $_POST['actividadTareaP'];
    $inicioPlanTareaP = $_POST['inicioPlanTareaP'];

    $finPlanTareaP = $_POST['finPlanTareaP'];
    $idTareaP = $_POST['idTareaP'];
    $nomCreadorTareaP = 'Yorgo Casimis Subiabre';
    $deptoCreadorTareaP = 1;
    $tipoDiasHabil = $_POST['tipoDiasHabil'];
    $idEquipo = $_POST['idEquipo'];
    $esEquipamiento = $_POST['esEquipamiento'];

    if($esEquipamiento !== 1 || $idEquipo < 1){
        header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?errorCargaEquipo=true');
    }else{
        $_SESSION['idEquipo'] = $idEquipo;
    }

    if ($idTareaP < 0 || $tipoDiasHabil == 'Seleccione...' || empty($tipoDiasHabil) || $deptoCreadorTareaP < 1 || empty($nomCreadorTareaP)) {
        header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
    } else {
        $feriadosChile = obtenerFeriadosChile();

        if ($tipoDiasHabil == 'Habiles') {

            $cantidadDiasTarea = obtenerDiasHabiles($inicioPlanTareaP, $finPlanTareaP, $feriadosChile);

        } else if ($tipoDiasHabil == 'Inhabiles') {

            $cantidadDiasTarea = obtenerDiasCorridos($inicioPlanTareaP, $finPlanTareaP, $feriadosChile);

        } else {
            header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
        }

        $sqlPlan = 'INSERT INTO tareas_carta_gantt(idTareaP, tituloTarea, actividadTareaP, inicioPlanTareaP,diasPlanTareaP,tipoDiasHabil, finPlanTareaP, inicioRealTareaP, finRealTareaP, porcentajeTareaP, idPadreTareaP, iniciadaTareaP, finalizadaTareaP, nomCreadorTareaP, deptoCreadorTareaP,idEquipamiento, funcionariosSeleccionados) ';
        $sqlPlan .= ' VALUES(NULL,"' . $tituloTareaP . '","' . $actividadTarea . '","' . $inicioPlanTareaP . '","' . $cantidadDiasTarea . '","' . $tipoDiasHabil . '","' . $finPlanTareaP . '",NULL,NULL,0,"' . $idTareaP . '",0,0,"' . $nomCreadorTareaP . '","' . $deptoCreadorTareaP . '","'.$idEquipo.'","'.$funcionarios.'")';
        $resPlan = mysqli_query($conexion, $sqlPlan);

        if ($resPlan) {
            $ultimoID = $conexion->insert_id;
            $_SESSION['idTareaP'] = $idTareaP;

            // Recorrer el array de funcionarios seleccionados
            foreach ($funcionariosSeleccionados as $funcionario) {
                $funcionario = mysqli_real_escape_string($conexion, $funcionario); // Evitar inyección SQL

                $folio = 'HBQP';
                $estado = 'Trabajo Ingresado';

                $servicioOrden = obtenerIDServicioControl();
                if ($servicioOrden <= 0) {
                    $servicioOrden = 0;
                }



                $sqlTG = "INSERT INTO trabajos_general (idTG,folioTG,solicitanteTG,detalleTG,detalleTurnoCamara,detalleTurnoLLE,detalleTurnoCA,detalleTurnoRondas,detalleOtros,fechaTG,estadoTG,turnoTG,tecnico1,fechaProgramacion,ticketAsociado,tipoTrabajo,departamento,formularioRondas,descripcion,servicio,idTareaProgramada,idEquipamiento) 
                VALUES(NULL,'$folio','$nomCreadorTareaP','','','','','','','$now','$estado','$turnoAsignado2','$funcionario','$inicioPlanTareaP',0,'$tipoTrabajo',$deptoCreadorTareaP,0,'$tituloTareaP',$servicioOrden,$ultimoID,$idEquipo)";
                $resTG = mysqli_query($conexion, $sqlTG);

                if ($resTG) {
                    $idTG = mysqli_insert_id($conexion);

                    if ($idTG < 10) {
                        $idRecepcion2 = "000" . $idTG;
                    } else if ($idTG > 9 && $idTG < 100) {
                        $idRecepcion2 = "00" . $idTG;
                    } else if ($idTG > 99 && $idTG < 1000) {
                        $idRecepcion2 = "0" . $idTG;
                    } else if ($idTG > 999) {
                        $idRecepcion2 = $idTG;
                    } else {
                        $idRecepcion2 = $idTG;
                    }

                    if (isset($_POST['checklist'], $_POST['cantidad'], $idTG)) {
                        // Capturar y limpiar datos
                        $checklistSeleccionado = trim($_POST['checklist']);
                        $cantidadCheck1 = intval($_POST['cantidad']); // Asegurar que sea un número

                        if (!empty($checklistSeleccionado) && $cantidadCheck1 > 0) {
                            // Evitar SQL Injection
                            $checklistItem = mysqli_real_escape_string($conexion, $checklistSeleccionado);

                            // Determinar el campoUpdate según el valor seleccionado
                            if (in_array($checklistItem, ['Pantallas Graficas', 'Logica de Programacion', 'Controlador Modelo ECB', 'TCC de UMA', 'VAV'])) {
                                $campoUpdate = 'detalleTG';
                            } elseif ($checklistItem === 'Unidades Control de Accesos') {
                                $campoUpdate = 'detalleTurnoCA';
                            } else {
                                $campoUpdate = 'detalleOtros';
                            }

                            // Insertar en la base de datos
                            $sqlChecklist = "INSERT INTO tipos_check_list (idTipoChecklist, tipo, cantidad, idTG) VALUES (NULL, '$checklistItem', $cantidadCheck1, $idTG)";
                            $resChecklist = mysqli_query($conexion, $sqlChecklist);

                            // Validar que la inserción fue exitosa
                            if (!$resChecklist) {
                                echo "Error en la consulta: " . mysqli_error($conexion);
                            }
                        }
                    } else {
                        $campoUpdate = 'detalleOtros';
                    }

                    if ($deptoCreadorTareaP == 1 || $deptoCreadorTareaP == '13') {
                        $str = 'I';
                    } else if ($deptoCreadorTareaP == 2) {
                        $str = 'EI';
                    } else if ($deptoCreadorTareaP == 3) {
                        $str = 'CLIMA';
                    } else if ($deptoCreadorTareaP == 4) {
                        $str = 'E';
                    } else if ($deptoCreadorTareaP == 5) {
                        $str = 'INFRA';
                    } else if ($deptoCreadorTareaP == 6) {
                        $str = 'EQMED';
                    } else if ($deptoCreadorTareaP == 7) {
                        $str = 'LOG';
                    } else if ($deptoCreadorTareaP == 8) {
                        $str = 'MOV';
                    } else if ($deptoCreadorTareaP == 10) {
                        $str = 'EDAC';
                    } else if ($deptoCreadorTareaP == 12) {
                        $str = 'INCEDIO';
                    } else {
                        $str = 'SDO';
                    }

                    $folioTG = 'OT' . '-' . $str . '-' . $idRecepcion2;

                    $sqlUpdate = "UPDATE trabajos_general SET folioTG = '$folioTG', $campoUpdate = '$actividadTarea' WHERE idTG = $idTG";
                    $resUpdate = mysqli_query($conexion, $sqlUpdate);

                    $detalleObs = 'Se ha ingresado la orden de trabajo ' . $folioTG . ' y se ha asignado a ' . $funcionario . ' para realizar el trabajo';

                    $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida,nomCreador,detalle,fechaSubida,idTG)
                    VALUES (NULL,'" . $nomCreadorTareaP . "','" . $detalleObs . "','" . $now . "',$idTG)";
                    $resAvance = mysqli_query($conexion, $sqlAvance);


                }


            }

            header('Location:../' . $rutaDireccion . '/equipo_menuAntiguo.php?exitoCrearPlanConOT=true');

        } else {
            header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
//            echo $sqlPlan;
//            exit();
        }
    }
} else {
    header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
}

