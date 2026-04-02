<?php

include('funciones.php');
include_once('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = '';
$rutaRol = '';

if (isset($_POST['rolUsuario'])) {

    $rolUsuario = trim($_POST['rolUsuario']);

    if (!empty($rolUsuario)) {
        if ($rolUsuario == "Administrador") {

            $rutaRol = "sdo-administrador/actas-funcionario.php";

        } else if ($rolUsuario == "Personal") {

            $rutaRol = "sdo-personal/actas-funcionario.php";

        } else if ($rolUsuario == "Directiva") {

            $rutaRol = "sdo-directiva";

        } else if ($rolUsuario == "Jefe Tecnico") {

            $rutaRol = "sdo-jefestecnicos/actas-funcionario.php";

        } else if ($rolUsuario == "Administrativo") {

            $rutaRol = "sdo-admtec/actas-listado.php";

        } else {

            header('Location: ../sdo-flujo/login_index.php?errorLogin=true');

        }
//
//        var_dump($_POST);
//        exit;

        if (isset($_POST['codigoTarjeta'], $_POST['idActa2'], $_POST['calidadContractual'], $_POST['nomTarjeta'], $_POST['rutTarjeta'], $_POST['numTelefono'], $_POST['unidadTarjeta'], $_POST['nomSolicitante'], $_POST['nomControlCentralizado'], $_POST['valorDiscapacidad'], $_POST['valorBeca'])) {



            $codigoTarjeta = trim($_POST['codigoTarjeta']);
            $idActa2 = trim($_POST['idActa2']);
            $calidadContractual = trim($_POST['calidadContractual']);
            $nomTarjeta = trim($_POST['nomTarjeta']);




            $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_LOWER, "UTF-8");
            $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_UPPER, "UTF-8");

            $rutTarjeta = trim($_POST['rutTarjeta']);
            $numTelefono = trim($_POST['numTelefono']);

            //OBTENER unidadTarjeta Y CAPITALIZARLO
            $unidadTarjeta = trim($_POST['unidadTarjeta']);
            $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_UPPER, "UTF-8");
            //FIN OBTENER unidadTarjeta Y CAPITALIZARLO


            $nomSolicitante = trim($_POST['nomSolicitante']);

            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_LOWER, "UTF-8");
            $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, "UTF-8");

            if ($unidadTarjeta == 'OTRO') {
                if (isset($_POST['unidadTarjeta2'], $_POST['nomSolicitante2'])) {
                    $unidadTarjeta2 = trim($_POST['unidadTarjeta2']);
                    $nomSolicitante2 = trim($_POST['nomSolicitante2']);

                    if (!empty($unidadTarjeta2) && !empty($nomSolicitante2)) {
                        $unidadTarjeta2 = mb_convert_case($unidadTarjeta2, MB_CASE_UPPER, "UTF-8");
                        $nomSolicitante2 = mb_convert_case($nomSolicitante2, MB_CASE_UPPER, "UTF-8");

                        $unidadTarjeta = $unidadTarjeta2;
                        $nomSolicitante = $nomSolicitante2;
                    }
                }
            }

            if ($nomSolicitante == 'OTRONOMBRE') {
                if (isset($_POST['nomSolicitante3'])) {
                    $nomSolicitante3 = trim($_POST['nomSolicitante3']);

                    if (!empty($nomSolicitante3)) {
                        $nomSolicitante3 = mb_convert_case($nomSolicitante3, MB_CASE_UPPER, "UTF-8");

                        $nomSolicitante = $nomSolicitante3;
                    }
                }
            }

            $nomControlCentralizado = trim($_POST['nomControlCentralizado']);

            $nomControlCentralizado = mb_convert_case($nomControlCentralizado, MB_CASE_LOWER, "UTF-8");
            $nomControlCentralizado = mb_convert_case($nomControlCentralizado, MB_CASE_UPPER, "UTF-8");


            $valorDiscapacidad = trim($_POST['valorDiscapacidad']);
            $valorBeca = trim($_POST['valorBeca']);


            $esDiscapacitado = 0;

            $esBecado = 0;

            date_default_timezone_set('America/Santiago');
//
//             echo 'codigoTarjeta: '.$codigoTarjeta.'<br><br>';
//             echo 'idActa2: '.$idActa2.'<br><br>';
//             echo 'calidadContractual: '.$calidadContractual.'<br><br>';
//             echo 'nomTarjeta: '.$nomTarjeta.'<br><br>';
//             echo 'rutTarjeta: '.$rutTarjeta.'<br><br>';
//             echo 'unidadTarjeta: '.$unidadTarjeta.'<br><br>';
//             echo 'nomSolicitante: '.$nomSolicitante.'<br><br>';
//             echo 'valorDiscapacidad: '.$valorDiscapacidad.'<br><br>';
//             echo 'valorBeca: '.$valorBeca.'<br><br>';
//
//            exit();

            if (empty($codigoTarjeta) || empty($idActa2) || empty($calidadContractual) || empty($nomTarjeta) || empty($rutTarjeta) || empty($unidadTarjeta) || empty($nomSolicitante) || empty($valorDiscapacidad) || empty($valorBeca)) {
                header('Location: ../' . $rutaRol . '?errorModActa=true');
            } else {


                if ($valorDiscapacidad == 'SI') {
                    $esDiscapacitado = 1;
                } else if ($valorDiscapacidad == 'NO') {
                    $esDiscapacitado = 0;
                } else {
                    $esDiscapacitado = 0;
                }

                if ($valorBeca == 'SI') {
                    $esBecado = 1;
                } else if ($valorBeca == 'NO') {
                    $esBecado = 0;
                } else {
                    $esBecado = 0;
                }

                $cadenaTexto = "A-" . $idActa2 . "-" . $codigoTarjeta;


                date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());
                $now2 = date('d-m-Y', time());

                $buscarFechaActa = buscarFechaActa($idActa2);
                $buscarFechaActa = date("d-m-Y", strtotime($buscarFechaActa));

                echo $now2 . '<br><br>' . $buscarFechaActa . '<br><br>';

                $fecha_actual = strtotime($now2);
                $buscarFechaActa2 = strtotime($buscarFechaActa);

                $tituloActivaActualiza = '';

                if ($fecha_actual > $buscarFechaActa2) {
                    $tituloActivaActualiza = 'Actualización';
                } else {
                    $tituloActivaActualiza = 'Activación';
                }

                $sql1 = "SELECT * FROM actas WHERE idActa = $idActa2";
                $res1 = mysqli_query($conexion,$sql1);
                while($row1 = $res1->fetch_assoc()){
                    $codigoTarjeta1 = $row1['codigoTarjeta'];
                    $nomTarjeta1 = $row1['nomTarjeta'];
                    $rutTarjeta1 = $row1['rutTarjeta'];
                    $unidadTarjeta1 = $row1['unidadTarjeta'];
                    $calidadContractual1 = $row1['calidadContractual'];
                    $numTelefono1 = $row1['numTelefono'];
                    $nomSolicitante1 = $row1['nomSolicitante'];
                    $personaBecada = $row1['personaBecada'];
                    $personaDisca = $row1['personaDiscapacitada'];
                    $controlCentralizado = $row1['nomControlCentralizado'];


                }
                $detalleObservacion = '';
                $codigoDetalle = 'El codigo de la tarjeta fue: '.$codigoTarjeta1;
                $nomTarjetaDetalle = 'El nombre de la tarjeta fue: '.$nomTarjeta1;
                $rutTarjetaDetalle = 'El rut de la tarjeta fue: '.$rutTarjeta1;
                $unidadTarjetaDetalle = 'La unidad de la tarjeta fue: '.$unidadTarjeta1;
                $calidadContractualDetalle = 'La calidad contractual fue: '.$calidadContractual1;
                $numTelefonoDetalle = 'El numero de telefono fue: '.$numTelefono1;
                $nomSolicitanteDetalle = 'El nombre del solicitante fue: '.$nomSolicitante1;
                $controlCentralizadoDetalle = 'El nombre del personal de control fue: '.$controlCentralizado;

                if($codigoTarjeta1 == $codigoTarjeta){
                    $codigoObs = 'Codigo Tarjeta:'.' NO';
                }else{
                    $codigoObs = 'Codigo Tarjeta:'.' SI';
                }

                if($nomTarjeta1 == $nomTarjeta){
                    $nomTarjetaObs = 'Nombre Tarjeta:'.' NO';
                }else{
                    $nomTarjetaObs = 'Nombre Tarjeta:'.' SI';
                }

                if($rutTarjeta1 == $rutTarjeta){
                    $rutTarjetaObs = 'Rut Tarjeta:'.' NO';
                }else{
                    $rutTarjetaObs = 'Rut Tarjeta:'.' SI';
                }

                if($unidadTarjeta1 == $unidadTarjeta){
                    $unidadTarjetaObs = 'Unidad Tarjeta:'.' NO';
                }else{
                    $unidadTarjetaObs = 'Unidad Tarjeta:'.' SI';
                }

                if($calidadContractual1 == $calidadContractual){
                    $calidadContractualObs = 'Calidad Contractual:'.' NO';
                }else{
                    $calidadContractualObs = 'Calidad Contractual:'.' SI';
                }

                if($numTelefono1 == $numTelefono){
                    $numTelefonoObs = 'Número Telefonico:'.' NO';
                }else{
                    $numTelefonoObs = 'Número Telefonico:'.' SI';
                }

                if($nomSolicitante1 == $nomSolicitante){
                    $nomSolicitanteObs = 'Nombre Solicitante:'.' NO';
                }else{
                    $nomSolicitanteObs = 'Nombre Solicitante:'.' SI';
                }

                if($personaBecada == $esBecado){
                    $personaBecadaObs = 'Persona Becada:'.' NO';
                }else{
                    $personaBecadaObs = 'Persona Becada:'.' SI';
                }

                if($personaDisca == $esDiscapacitado){
                    $personaDiscaObs = 'Persona Discapacitada:'.' NO';
                }else{
                    $personaDiscaObs = 'Persona Discapacitada:'.' SI';
                }

                if($codigoTarjeta1 == $codigoTarjeta && $nomTarjeta1 == $nomTarjeta && $rutTarjeta1 == $rutTarjeta && $unidadTarjeta1 == $unidadTarjeta && $calidadContractual1 == $calidadContractual && $numTelefono1 == $numTelefono && $nomSolicitante1 == $nomSolicitante && $personaBecada == $esBecado && $personaDisca == $esDiscapacitado){
                    $detalleObservacion .= 'No se ha realizado modificaciones';
                }else{
                    $detalleObservacion .= 'Se ha actualizado esta acta con los siguientes datos: '.'<br>'.$codigoObs.'<br>'.$nomTarjetaObs.'<br>'.$rutTarjetaObs.'<br>'.$unidadTarjetaObs.'<br>'.$calidadContractualObs.'<br>'.$numTelefonoObs.'<br>'.$nomSolicitanteObs.'<br>'.$personaBecadaObs.'<br>'.$personaDiscaObs.'<br>'.'<br>';
                    $detalleObservacion .= 'Los datos antiguos fueron los siguientes: '.'<br>'.$codigoDetalle.'<br>'.$nomTarjetaDetalle.'<br>'.$rutTarjetaDetalle.'<br>'.$unidadTarjetaDetalle.'<br>'.$calidadContractualDetalle.'<br>'.$numTelefonoDetalle.'<br>'.$nomSolicitanteDetalle.'<br>'.$controlCentralizadoDetalle;
                }

                $sqlObservacion = "INSERT INTO actas_observacion (idActasObs,fechaIngreso,nomFuncionario,rutFuncionario,detalleObservacion,calidadContractual,unidadFuncionario,creadorObs,idActa)
                         VALUES (NULL,'".$now."','".$nomTarjeta."','".$rutTarjeta."','".$detalleObservacion."','".$calidadContractual."','".$unidadTarjeta."','".$nomControlCentralizado."',$idActa2)";
                $resObservacion = mysqli_query($conexion,$sqlObservacion);


                $queryModificar = "UPDATE actas SET folioActa = '" . $cadenaTexto . "', fechaActa = '".$now."' , codigoTarjeta = '" . $codigoTarjeta . "', nomTarjeta = '" . $nomTarjeta . "', rutTarjeta = '" . $rutTarjeta . "', unidadTarjeta = '" . $unidadTarjeta . "', nomSolicitante = '" . $nomSolicitante . "', nomControlCentralizado = '" . $nomControlCentralizado . "', calidadContractual = '" . $calidadContractual . "', numTelefono = '" . $numTelefono . "', tipoActa = '".$tituloActivaActualiza."', personaDiscapacitada = '".$esDiscapacitado."', personaBecada = '".$esBecado."' WHERE idActa = " . $idActa2;

                $resModificar = mysqli_query($conexion, $queryModificar);

                if ($resModificar) {

                    header('Location: ../' . $rutaRol . '?exitoModActa=true');
                } else {
                    header('Location: ../' . $rutaRol . '?errorModActa1=true');
                }
            }
        } else {
            header('Location: ../' . $rutaRol . '?errorModActa2=true');
        }
    } else {
        header('Location: sdo-flujo/login_index.php?errorLogin=true');
    }
} else {
    header('Location: sdo-flujo/login_index.php?errorLogin=true');
}
