<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establecer zona horaria
date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');

// Validar que existan las variables de sesión necesarias
if (
    !isset($_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'])
) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

// VARIABLES DE SESIÓN
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = strtolower(trim($_SESSION['rolUsuario']));

$tipoUsuario = $rolUsuario;
$rutaDireccion = '';

switch ($rolUsuario) {
    case 'personal':
        $rutaDireccion = 'sdo-personal';
        break;
    case 'administrativo':
        $rutaDireccion = 'sdo-admtec';
        break;
    case 'bodega pañol':
        $rutaDireccion = 'sdo-bodega-panol';
        break;
    case 'jefe tecnico':
        $rutaDireccion = 'sdo-jefestecnicos';
        break;
    case 'administrador':
        $rutaDireccion = 'sdo-administrador';
        break;
    case 'directiva':
        $rutaDireccion = 'sdo-directiva';
        break;
    case 'oficina tecnica':
        $rutaDireccion = 'sdo-oficinaTecnica';
        break;
    default:
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit();
}

$campoUpdate = '';

if (isset($_POST['tituloTareaP'], $_POST['turnoAsignado'], $_POST['funcionarioMPIUCA'], $_POST['inicioPlanTareaP'], $_POST['nomCreadorTareaP'], $_POST['deptoCreadorTareaP'])) {

    $tituloTareaP = $_POST['tituloTareaP'];
    $turnoAsignado = $_POST['turnoAsignado'];
    $funcionarioMPIUCA = $_POST['funcionarioMPIUCA'];
    $inicioPlanTareaP = $_POST['inicioPlanTareaP'];
    $idTareaP = 0;
    $nomCreadorTareaP = $_POST['nomCreadorTareaP'];
    $deptoCreadorTareaP = $_POST['deptoCreadorTareaP'];

    if (empty($funcionarioMPIUCA)) {
        header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
    }

    if ($turnoAsignado == 1) {
        $turnoAsignado2 = 'Personal Diurno';
    } else if ($turnoAsignado == 2) {
        $turnoAsignado2 = 'Personal Nocturno';
    } else {
        $turnoAsignado2 = '';
    }

    $tipoTrabajo = 'MPI';

    $nomCreadorTareaP = 'Jorge Cantillano';
    $deptoCreadorTareaP = 1;

    $idEquipo = $_POST['idEquipo'];
    $esEquipamiento = $_POST['esEquipamiento'];

    $idEquipo = (int)$idEquipo;
    $esEquipamiento = (int)$esEquipamiento;

    if ($esEquipamiento !== 1 || $idEquipo < 1) {
//        header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?errorCargaEquipo=true');

        echo "'$esEquipamiento' -- '$idEquipo'";
    } else {
        $_SESSION['idEquipo'] = $idEquipo;

        if ($turnoAsignado == 1 || $turnoAsignado == 2) {

            if ($turnoAsignado == 1) {
                $finPlanTareaP = date('Y-m-d', strtotime($inicioPlanTareaP));
            } else if ($turnoAsignado == 2) {
                $finPlanTareaP = date('Y-m-d', strtotime($inicioPlanTareaP . ' + 1 days'));
            }

            $feriadosChile = obtenerFeriadosChile();

            $cantidadDiasTarea = obtenerDiasCorridos($inicioPlanTareaP, $finPlanTareaP, $feriadosChile);

            $sqlPlan = 'INSERT INTO tareas_carta_gantt(idTareaP, tituloTarea, actividadTareaP, inicioPlanTareaP,diasPlanTareaP,tipoDiasHabil, finPlanTareaP, inicioRealTareaP, finRealTareaP, porcentajeTareaP, idPadreTareaP, iniciadaTareaP, finalizadaTareaP, nomCreadorTareaP, deptoCreadorTareaP,idEquipamiento, funcionariosSeleccionados) ';
            $sqlPlan .= ' VALUES(NULL,"' . $tituloTareaP . '","' . $tituloTareaP . '","' . $inicioPlanTareaP . '","' . $cantidadDiasTarea . '","Inhabiles","' . $finPlanTareaP . '",NULL,NULL,0,"' . $idTareaP . '",0,0,"' . $nomCreadorTareaP . '","' . $deptoCreadorTareaP . '","' . $idEquipo . '","' . $funcionarioMPIUCA . '")';

//            echo $sqlPlan;

            $resPlan = mysqli_query($conexion, $sqlPlan);

            if ($resPlan) {
                $ultimoID = $conexion->insert_id;
                $_SESSION['idTareaP'] = $idTareaP;

                $funcionario = mysqli_real_escape_string($conexion, $funcionarioMPIUCA); // Evitar inyección SQL

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

                    $checkListSeleccionado = 'Unidades Control de Accesos';
                    $cantidadCheck1 = 1;
                    $campoUpdate = 'detalleTurnoCA';


                    $checklistItem = mysqli_real_escape_string($conexion, $checkListSeleccionado);

                    $sqlChecklist = "INSERT INTO tipos_check_list (idTipoChecklist, tipo, cantidad, idTG) VALUES (NULL, '$checklistItem', $cantidadCheck1, $idTG)";
                    $resChecklist = mysqli_query($conexion, $sqlChecklist);

                    // Validar que la inserción fue exitosa
                    if (!$resChecklist) {
                        echo "Error en la consulta: " . mysqli_error($conexion);
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

                    $sqlUpdate = "UPDATE trabajos_general SET folioTG = '$folioTG', $campoUpdate = '$tituloTareaP' WHERE idTG = $idTG";
                    $resUpdate = mysqli_query($conexion, $sqlUpdate);

                    $detalleObs = 'Se ha ingresado la orden de trabajo ' . $folioTG . ' y se ha asignado a ' . $funcionario . ' para realizar el trabajo';

                    $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida,nomCreador,detalle,fechaSubida,idTG)
                    VALUES (NULL,'" . $nomCreadorTareaP . "','" . $detalleObs . "','" . $now . "',$idTG)";
                    $resAvance = mysqli_query($conexion, $sqlAvance);
                }

                header('Location:../' . $rutaDireccion . '/equipo_menuAntiguo.php?exitoCrearPlanConOT=true');

            } else {
                header('Location: ../' . $rutaDireccion . '/equipo_menuAntiguo.php?errorCrearPlanConOT=true');
//            echo $sqlPlan;
//            exit();
            }


        } else {
            header('Location: ../' . $rutaDireccion . '/equipamiento_hbqp.php?errorCrearPlanConOT=true');

        }

    }

}