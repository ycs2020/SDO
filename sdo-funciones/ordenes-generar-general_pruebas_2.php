<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['nomSolicitante'], $_POST['listadoUsuarios'], $_POST['servicioOrden'], $_POST['departamentoSolicitante'], $_POST['recintoOrden'],  $_POST['fechaActualOrden'])) {
    $ticketAsociado = $_POST['ticketAsociado'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
    $nomSolicitante = trim($_POST['nomSolicitante']);
    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $horaInicio = $_POST['horarioInicio'];
    $horarioTermino = $_POST['horarioTermino'];
    $listadoUsuarios = $_POST['listadoUsuarios'];


    if (is_array($listadoUsuarios)) {
        sort($listadoUsuarios);
        // Unir los elementos del array con ' - ' como delimitador
        $listadoUsuarios = implode(' - ', $listadoUsuarios);
    } else {
        $listadoUsuarios = '';
    }

    $listadoUsuarios = trim($listadoUsuarios);


    if($horaInicio == '' || empty($horaInicio)){
        $horaInicio = '0000-00-00 00:00:00';
    }

    if($horarioTermino == '' || empty($horarioTermino)){
        $horarioTermino = '0000-00-00 00:00:00';
    }

    if($idHoja == ''){
        $idHoja = 0;
    }

    if($idCategoria == ''){
        $idCategoria = 0;
    }

    $nomTecnicoRecepcionador = trim($_POST['nomTecnicoRecepcionador']);

    $servicioOrden = trim($_POST['servicioOrden']);

    if ($servicioOrden == 0 || $servicioOrden == 'Otro' || $servicioOrden == 'otro') {
        if (isset($_POST['otroServicio'])) {

            $otroServicio = trim($_POST['otroServicio']);

            if (!empty($otroServicio)) {
                $otroServicio = str_replace("'", "\'", $otroServicio);

                $buscarServicio = buscarServicio($otroServicio);

                if ($buscarServicio == 0) {

                    $queryOtroServicio = "INSERT INTO servicios(idServicio,nomServicio) VALUES (NULL,'" . $otroServicio . "')";

                    // echo $queryOtroServicio;

                    $resOtroServicio = mysqli_query($conexion, $queryOtroServicio);

                    $servicioOrden = mysqli_insert_id($conexion);
                } else {
                    $servicioOrden = $buscarServicio;
                }
            } else {
            }
        } else {
        }
    }

    $recintoOrden = trim($_POST['recintoOrden']);
    $pmaOrden = trim($_POST['pmaOrden']);
    $coordenadasOrden = trim($_POST['coordenadasOrden']);
    $tipoMantencion = $_POST['tipoMantencion'];
    $radioInput = $_POST['radio'];
    $ordenCompra = $_POST['ordenCompra'];
    $otroTipoTrabajo = $_POST['otroTipoTrabajo'];
    $trabajoRealizado = trim($_POST['trabajoRealizado']);
    $problemaPresenta = trim($_POST['problemaPresenta']);
    $accionesRealiza = trim($_POST['accionesRealiza']);
    $comentariosTrabajos = trim($_POST['comentariosTrabajos']);
    $fechaActualOrden = $_POST['fechaActualOrden'];
    $departamentoSolicitante = trim($_POST['departamentoSolicitante']);
    $nomDepartamentoSolicitante = trim(getNomDepartamento($departamentoSolicitante));
    $selectDepa = $_POST['selectDepa'];

    // //OBTIENE NOMBRE DE PERSONAL
    // $sqlPersonal = "SELECT * FROM personal WHERE idPersonal  = $idUsuario AND departamentoPersonal = $departamentoSolicitante";
    // echo $sqlPersonal;
    // $resPersonal = mysqli_query($conexion, $sqlPersonal);


    // while ($rowPersonal = $resPersonal->fetch_assoc()) {
    //     $datos = [];
    //     $datos['nomPersonal'] = $rowPersonal['nomPersonal'];

    //     $nomTecnicoRecepcionador = $datos['nomPersonal'];
    // }


    // DATOS SOLICITANTE
    echo "Nombre Solicitante = " . $nomSolicitante . "<br>";
    echo "Nombre Tecnico Recepcionador = " . $nomTecnicoRecepcionador . "<br>";
    echo "Departamento Solicitante = " . $departamentoSolicitante . "<br>";
    echo "Nombre departamento Solicitante = " . $nomDepartamentoSolicitante . "<br>";
    echo "Servicio = " . $servicioOrden . "<br>";
    echo "Recinto = " . $recintoOrden . "<br>";
    echo "PMA = " . $pmaOrden . "<br>";
    echo "Coordenadas = " . $coordenadasOrden . "<br>";

    //DATOS EQUIPO
    $nomEquipo = $_POST['nomEquipo'];
    $numInventario = $_POST['numInventario'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $numSerie = $_POST['numSerie'];
    $selectservicio = $_POST['selectservicio'];
    $categoriaEquipo = $_POST['categoriaEquipo'];
    $inputpiso = $_POST['inputpiso'];
    $idDepaPertenece = $_POST['depa'];
    $nomEquipo1 = $_POST['nomEquipo1'];
    $numInventario1 = $_POST['numInventario1'];
    $marcaEquipo1 = $_POST['marcaEquipo1'];
    $modeloEquipo1 = $_POST['modeloEquipo1'];
    $numSerie1 = $_POST['numSerie1'];
    $idCheck = $_POST['cbox1'];
    $inputIDUnico = $_POST['inputIDUnico'];
    $estadoEquipo = 'Operativo';
    $idDepa = $_POST['idDepa'];
    $tipoAcuerdo = $_POST['tipoAcuerdo'];
    $oc = $_POST['oc'];
    $estadoEquipo = $_POST['estadoEquipo'];


    if($inputpiso == '' || empty($inputpiso)){
        $inputpiso = 0;
    }

    echo "Nombre Equipo = " . $nomEquipo . "<br>";
    echo "Numero Inventario = " . $numInventario . "<br>";
    echo "Marca Equipo = " . $marcaEquipo . "<br>";
    echo "Modelo Equipo = " . $modeloEquipo . "<br>";
    echo "Numero Serie = " . $numSerie . "<br>";
    echo "IDCHECKED = ". $idCheck."<br>";

    //DETALLES SOLICITUD
    echo "Tipo de Mantención = " . $tipoMantencion . "<br>";
    echo "Garantia o Convenio = " . $radioInput . "<br>";
    echo "Orden de Compra = " . $ordenCompra . "<br>";
    echo "Otro tipo de trabajo = " . $otroTipoTrabajo . "<br>";
    echo "Trabajo realizado = " . $trabajoRealizado . "<br>";
    echo "Problema que presenta = " . $problemaPresenta . "<br>";
    echo "Acciones que realiza = " . $accionesRealiza . "<br>";
    echo "Comentarios = " . $comentariosTrabajos . "<br>";
    echo "Fecha de Orden = " . $fechaActualOrden . "<br>";

    if($tipoAcuerdo == '' || empty($tipoAcuerdo)){
        $tipoAcuerdo = null;
    }

    if (!empty($trabajoRealizado)) {

        $trabajoRealizado = str_replace("'", "\'", $trabajoRealizado);
    }

    if (!empty($problemaPresenta)) {

        $problemaPresenta = str_replace("'", "\'", $problemaPresenta);
    }

    if (!empty($accionesRealiza)) {

        $accionesRealiza = str_replace("'", "\'", $accionesRealiza);
    }

    if (!empty($comentariosTrabajos)) {

        $comentariosTrabajos = str_replace("'", "\'", $comentariosTrabajos);
    }

    if ($nomSolicitante == '' || $nomSolicitante == ' ' || empty($nomSolicitante) || $servicioOrden == '' || $servicioOrden == ' ' || empty($servicioOrden) || $recintoOrden == '' || $recintoOrden == ' ' || empty($recintoOrden)) {
        header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar1=true');
    } else {
        if ($departamentoSolicitante == '' || $departamentoSolicitante == ' ' || empty($departamentoSolicitante)  || $trabajoRealizado == '' || $trabajoRealizado == ' ' || empty($trabajoRealizado)) {
            header('Location: ../'.$rutaDireccion . '/ordenes-formulario-general.php?errorGenerar2=true');
        } else {
            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

            $año = date('Y');
//            $selectAct = "SELECT numOrden FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = 2024 ORDER BY fechaOrden DESC LIMIT 1";
            $selectAct = "SELECT numOrden FROM ordenes_trabajos_general_pruebas_2 WHERE YEAR(fechaOrden) = ".$año." ORDER BY fechaOrden DESC LIMIT 1";
            $resAct = mysqli_query($conexion,$selectAct);
            if ($resAct->num_rows == 0) {
                $numOrden = 1;
            }else{

                $fila = $resAct->fetch_assoc();
                $numOrden = $fila['numOrden'];
                $numOrden = $numOrden + 1;
            }

            $pieces = explode(" ", $nomDepartamentoSolicitante);

            $str = "";
            foreach ($pieces as $piece) {
                $str .= mb_strtoupper($piece[0], 'UTF-8');
            }

            $folioOrden = "HBQP-HT-A".$año."-" . $str . '-' . $numOrden;

            if($idCheck == '' || empty($idCheck)){
                $queryOrden = "INSERT INTO ordenes_trabajos_general_pruebas_2(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria,horaInicio,horaTermino,tipoAcuerdo,estadoEquipo,oc)
                VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $listadoUsuarios . "','" . $departamentoSolicitante . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaOrden . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $trabajoRealizado . "','" . $problemaPresenta . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" . $ticketAsociado . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'".$horaInicio."','".$horarioTermino."','".$tipoAcuerdo."','".$estadoEquipo."','".$oc."')";
                echo $queryOrden;
                //$resOrden = mysqli_query($conexion, $queryOrden);
            }else{
                if($rolUsuario2 == 'administrador' || $rolUsuario == 'oficina tecnica'){
                    //$selectDepa
                    $sql = "INSERT INTO equipamiento_hbqp(id,identificadorUnico,numeroInventario,nombreEquipo,marca,modelo,idServicio,numeroSerie,idCategoria,piso,idDepaPertenece,estadoEquipo)
                            VALUES (NULL,'".$inputIDUnico."','".$numInventario1."','".$nomEquipo1."','".$marcaEquipo1."','".$modeloEquipo1."',$selectservicio,'".$numSerie1."',$categoriaEquipo,$inputpiso,$selectDepa,'".$estadoEquipo."')";
                }else{
                    $sql = "INSERT INTO equipamiento_hbqp(id,identificadorUnico,numeroInventario,nombreEquipo,marca,modelo,idServicio,numeroSerie,idCategoria,piso,idDepaPertenece,estadoEquipo)
                            VALUES (NULL,'".$inputIDUnico."','".$numInventario1."','".$nomEquipo1."','".$marcaEquipo1."','".$modeloEquipo1."',$selectservicio,'".$numSerie1."',$categoriaEquipo,$inputpiso,$idDepaPertenece,'".$estadoEquipo."')";
                }

                echo $sql;
            }
        }
    }
}