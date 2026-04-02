<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];
if($idDepartamento == 13){
    $idDepartamento = 1;
}
//echo $idDepartamento.'<br><br>';
//exit();
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if (isset($_POST['fechaMonitoreo'], $_POST['tipoFormulario'], $_POST['personaAsignada'], $_POST['turnoAsignado'], $_POST['creadorOT'])) {
    $fechaMonitoreo = $_POST['fechaMonitoreo'];
    $tipoFormulario = $_POST['tipoFormulario'];
    $personaAsignada = $_POST['personaAsignada'];
    $turnoAsignado = $_POST['turnoAsignado'];
//
//    echo $fechaMonitoreo.'<br><br>';
//    echo $tipoFormulario.'<br><br>';
//    echo $personaAsignada.'<br><br>';
//    echo $turnoAsignado.'<br><br>';

    if($turnoAsignado == 1) {
        $turnoAsignado2 = 'Personal Diurno';
    }else if($turnoAsignado == 2) {
        $turnoAsignado2 = 'Personal Nocturno';
    }else{
        $turnoAsignado2 = '';
    }

    $creadorOT = $_POST['creadorOT'];
    $folio = 'HBQP';
    $estado = 'Trabajo Ingresado';
    $descripcion = 'Monitoreo y Control de BMS';


    $servicioOrden = obtenerIDServicioControl();
    if ($servicioOrden <= 0) {
        $servicioOrden = 0;
    }

    if ($turnoAsignado !== 1 && $turnoAsignado !== 2 && $tipoFormulario < 0) {
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorCrearOT=true');

    } else {

        if ($tipoFormulario == 0) {

            $detalleTurno = 'Redactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
            $detalleTurnoCamara = '';
            $detalleTurnoLLE = '';
            $detalleTurnoCA = '';
            $detalleTurnoRondas = '';
            $detalleOtros = 'Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.';


        }else if($turnoAsignado == 1 && $tipoFormulario == 1){

            $detalleTurno = 'Revisión e inspección de consolas de alarma y tablas resúmenes de equipos de clima pisos 3 al 6 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
            $detalleTurnoCamara = '';
            $detalleTurnoLLE = '';
            $detalleTurnoCA = '';
            $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.';
            $detalleOtros = 'Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.';

        }else if($turnoAsignado == 2 && $tipoFormulario == 1){

            $detalleTurno = 'Redactar correo dirigido al servicio de pabellón, adjuntando tabla con parámetros de temperatura y humedad (Pabellón N°2 al 10).\nRevisar sistema de Ticket (SIGH) y derivar al departamento que corresponda.\nRevisión e inspección de consolas de alarma y tablas resúmenes de equipos de clima pisos 3 al 6 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
            $detalleTurnoCamara = '';
            $detalleTurnoLLE = '';
            $detalleTurnoCA = 'Entrega de permisos correspondientes en software de control de accesos (formato de solicitud de permisos).';
            $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.';
            $detalleOtros = 'Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.';

        }else if($turnoAsignado == 1 && $tipoFormulario == 2){

            $detalleTurno = 'Revisión e inspección de parámetros de las 74 unidades manejadoras de aire y tablas resúmenes de equipos de clima pisos subterráneo al 2 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
            $detalleTurnoCamara = '';
            $detalleTurnoLLE = '';
            $detalleTurnoCA = '';
            $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.';
            $detalleOtros = 'Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.';

        }else if($turnoAsignado == 2 && $tipoFormulario == 2){

            $detalleTurno = 'Reiniciar biométrico de bicicletero a las 07:00 hrs. (presionar interruptor en terreno).\nRevisar sistema de Ticket (SIGH) y derivar al departamento que corresponda.\nRevisión e inspección de parámetros de las 74 unidades manejadoras de aire y tablas resúmenes de equipos de clima pisos subterráneo al 2 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
            $detalleTurnoCamara = '';
            $detalleTurnoLLE = '';
            $detalleTurnoCA = 'Entrega de permisos correspondientes en software de control de accesos (formato de solicitud de permisos).';
            $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de sensor de inundación ubicado en sala de caldera.\nRevisar estado de disyuntores trifásicos de bombas.';
            $detalleOtros = 'Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.';

        }

    }


    $sqlTG = "INSERT INTO trabajos_general (idTG,folioTG,solicitanteTG,detalleTG,detalleTurnoCamara,detalleTurnoLLE,detalleTurnoCA,detalleTurnoRondas,detalleOtros,fechaTG,estadoTG,turnoTG,tecnico1,fechaProgramacion,ticketAsociado,tipoTrabajo,departamento,formularioRondas,descripcion,servicio)
     VALUES(NULL,'$folio','" . $creadorOT . "','" . $detalleTurno . "','" . $detalleTurnoCamara . "','" . $detalleTurnoLLE . "','" . $detalleTurnoCA . "','" . $detalleTurnoRondas . "','" . $detalleOtros . "','" . $now . "','" . $estado . "','" . $turnoAsignado2 . "','" . $personaAsignada . "','" . $fechaMonitoreo . "',0,'',$idDepartamento,$tipoFormulario,'$descripcion',$servicioOrden)";
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

        if ($idDepartamento == 1 || $idDepartamento == 13) {
            $str = 'I';
        } else if ($idDepartamento == 2) {
            $str = 'EI';
        } else if ($idDepartamento == 3) {
            $str = 'CLIMA';
        } else if ($idDepartamento == 4) {
            $str = 'E';
        } else if ($idDepartamento == 5) {
            $str = 'INFRA';
        } else if ($idDepartamento == 6) {
            $str = 'EQMED';
        } else if ($idDepartamento == 7) {
            $str = 'LOG';
        } else if ($idDepartamento == 8) {
            $str = 'MOV';
        } else if ($idDepartamento == 10) {
            $str = 'EDAC';
        } else if ($idDepartamento == 12) {
            $str = 'INCEDIO';
        } else {
            $str = 'SDO';
        }

        $folioTG = 'OT' . '-' . $str . '-' . $idRecepcion2;

        $sqlUpdate = "UPDATE trabajos_general SET folioTG = '$folioTG' WHERE idTG = $idTG";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        $detalleObs = 'Se ha ingresado la orden de trabajo ' . $folioTG . ' y se ha asignado a ' . $personaAsignada . ' para realizar el trabajo';

        $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida,nomCreador,detalle,fechaSubida,idTG)
        VALUES (NULL,'" . $creadorOT . "','" . $detalleObs . "','" . $now . "',$idTG)";
        $resAvance = mysqli_query($conexion, $sqlAvance);

        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?exitoOT=true');

    } else {
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
    }
} else {
    header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
}




