<?php
$mostrarOcultar = deshabilitarOpciones();
if ($mostrarOcultar == 'Mostrar') {
    ?>
    <?php
} else {
    header('Location: ../sdo-flujo/login_mantenimiento.php');
}
?>
<?php

//
// $nombreArchivo = basename(__FILE__);
//
// //Ruta completa del script actual
// $rutaCompleta = $_SERVER['SCRIPT_FILENAME'];
//
//  //Ruta relativa al documento raíz
// $rutaRelativa = $_SERVER['PHP_SELF'];
// //
// //// URI solicitada
// $uriSolicitada = $_SERVER['REQUEST_URI'];
// //
// //
// //
// //// Llama a la función para verificar la IP y el tiempo
// if (verificarIP()) {
//     // El acceso está permitido, realiza otras acciones aquí
//     // echo '<script language="javascript">alert("Bienvenido, la IP está autorizada.");</script>';
// }
// function verificarIP() {
//     // IP permitida
//     $ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
//     $ipPermitida2 = '10.69.250.19'; // Reemplaza con la IP permitida
//     $ipPermitida3 = '10.69.250.205'; // Reemplaza con la IP permitida
// //
//     // Obtén la IP del cliente
//     $ipCliente = $_SERVER['REMOTE_ADDR'];
// //
// /// // Verifica si la IP del cliente es la permitida
//     if ($ipCliente === $ipPermitida || $ipCliente === $ipPermitida2 || $ipCliente === $ipPermitida3) {
//         // Verifica también el rango de tiempo permitido (16:30 - 19:30 hora chilena)
//         $horaActual = new DateTime('now', new DateTimeZone('America/Santiago'));
//        $horaInicio = new DateTime('16:30', new DateTimeZone('America/Santiago'));
//         $horaFin = new DateTime('19:30', new DateTimeZone('America/Santiago'));
//
// //       if ($horaActual >= $horaInicio && $horaActual <= $horaFin) {
// //            // La IP y el tiempo son permitidos, permite el acceso
// //           return true;
// //        } else {
// //            // // Fuera del rango de tiempo permitido, muestra un mensaje o redirige
// //           header('Location: ../sdo-flujo/login_mantenimiento.php?fueraDeTiempo=true');
// //           return true;
// //        }
//         return true;
//     } else {
//         // La IP no es permitida, muestra un mensaje o redirige
//         header('Location: ../sdo-flujo/login_mantenimiento.php?enMantencion=true');
//     }
// }

function deshabilitarOpciones()
{

    date_default_timezone_set('America/Santiago');
    $ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
    $ipPermitida2 = '10.69.250.19'; // Reemplaza con la IP permitida
    $ipPermitida3 = '10.69.250.205'; // Reemplaza con la IP permitida
    //
    // Obtén la IP del cliente
    $ipCliente = $_SERVER['REMOTE_ADDR'];
    //
    /// // Verifica si la IP del cliente es la permitida
// Verifica si la IP del cliente es la permitida y si la hora actual está entre las 13:50 y las 17:15
    $horaActual = new DateTime();
    $horaLimiteInicio = new DateTime('17:05');
    $horaLimiteFin = new DateTime('21:15');


    if ($ipCliente === $ipPermitida || $ipCliente === $ipPermitida2 || $ipCliente === $ipPermitida3) {
        return 'Mostrar';
    } else {
        if ($horaActual >= $horaLimiteInicio && $horaActual <= $horaLimiteFin) {
            return 'Mostrar';
//            return 'Ocultar';
        } else {
            return 'Mostrar';
        }
    }
}

function obtieneCantidadTicketCarpeta($carpeta)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE idCategoria = $carpeta AND (ticketAsociado != '' AND ticketAsociado != '0')";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $total = $row['total'];
        } else {
            $total = 0;
        }
    } else {
        $total = 0;
    }

    return $total;
}

function sumartablaagua($anio)
{
    include 'conexion.php';
    $sqlSumaAGUA = "SELECT SUM(consumoAgua) as totalAGUA FROM agua_administracion WHERE tipohospital = 'HBQP' AND YEAR(fechaDocumento) = '$anio'";
    $resSumaAGUA = mysqli_query($conexion, $sqlSumaAGUA);
    while ($rowSumaAGUA = $resSumaAGUA->fetch_assoc()) {
        $totalAGUA = $rowSumaAGUA['totalAGUA'];
    }
    return $totalAGUA;
}

function sumartotalmontoagua($anioAgua)
{
    include 'conexion.php';
    $sqlMontoAGUA = "SELECT SUM(montoTotalMes) as totalMontoAGUA FROM agua_administracion WHERE tipoHospital = 'HBQP' AND YEAR(fechaDocumento) = '$anioAgua'";
    $resMontoAGUA = mysqli_query($conexion, $sqlMontoAGUA);
    while ($rowMontoAGUA = $resMontoAGUA->fetch_assoc()) {
        $totalMontoAGUA = $rowMontoAGUA['totalMontoAGUA'];
    }
    return $totalMontoAGUA;
}

function valorTotalNetogases($anio)
{
    include 'conexion.php';
    $sqlSuma = "SELECT SUM(valorTotalNeto) as total FROM gases_clinicos WHERE YEAR(fechaIngreso) = '$anio'";
    $resSuma = mysqli_query($conexion, $sqlSuma);
    while ($rowSuma = $resSuma->fetch_assoc()) {
        $datos = [];
        $datos['total'] = $rowSuma['total'];
        $total = $datos['total'];
    }
    return $total;
}

function valorTotalMontoGases($anio)
{
    include 'conexion.php';
    $sqlMonto = "SELECT SUM(valorTotalIva) as totalMonto FROM gases_clinicos WHERE YEAR(fechaIngreso) = '$anio'";
    $resMonto = mysqli_query($conexion, $sqlMonto);
    while ($rowMonto = $resMonto->fetch_assoc()) {
        $datosMonto = [];
        $datosMonto['totalMonto'] = $rowMonto['totalMonto'];
        $totalMonto = $datosMonto['totalMonto'];
    }
    return $totalMonto;
}

function compruebaRegistroFalla($id)
{
    include 'conexion.php';
    $sql = "SELECT * FROM historial_cambios_equipos_falla WHERE idEquipo = $id ";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['idEquipo'] = $row['idEquipo'];
        $idEquipo = $datos['idEquipo'];
    }
    if ($idEquipo !== '') {
        return "Si";
    } else {
        return "No";
    }
}


function compruebaRegistroObservacion($id)
{
    include 'conexion.php';
    $sql = "SELECT * FROM historial_cambios_equipos_observacion WHERE idEquipo = $id ";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['idEquipo'] = $row['idEquipo'];
        $idEquipo = $datos['idEquipo'];
    }
    if ($idEquipo !== '') {
        return "Si";
    } else {
        return "No";
    }
}

function obtieneCantidadTiposMantencion($carpeta, $tipo)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE tipoMantencion =  '$tipo' AND idCategoria = $carpeta ";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['total'] = $row['total'];
        $total = $datos['total'];
    }
    return $total;
}

function obtieneGastoTiposMantencion($carpeta, $tipo)
{
    include 'conexion.php';
    $sql = "SELECT SUM(costoTotal) as total FROM gasto_equipo_carpeta WHERE tipoMantencion =  '$tipo' AND idCategoria = $carpeta ";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['total'] = $row['total'];
        $total = $datos['total'];
    }
    return $total;
}


function obtenerCantidadGastoTipoMantencion($carpeta, $tipo)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as totalCantidadGasto FROM gasto_equipo_carpeta WHERE tipoMantencion =  '$tipo' AND idCategoria = $carpeta ";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['totalCantidadGasto'] = $row['totalCantidadGasto'];
        $totalCantidadGasto = $datos['totalCantidadGasto'];
    }
    return $totalCantidadGasto;
}


function obtieneTotalHTTipo($tipo, $numMes)
{

    // if ($mes == 'Enero') {
    //     $numMes = '01';
    // } else if ($mes == 'Febrero') {
    //     $numMes = '02';
    // } else if ($mes == 'Marzo') {
    //     $numMes = '03';
    // } else if ($mes == 'Abril') {
    //     $numMes = '04';
    // } else if ($mes == 'Mayo') {
    //     $numMes = '05';
    // } else if ($mes == 'Junio') {
    //     $numMes = '06';
    // } else if ($mes == 'Julio') {
    //     $numMes = '07';
    // } else if ($mes == 'Agosto') {
    //     $numMes = '08';
    // } else if ($mes == 'Septiembre') {
    //     $numMes = '09';
    // } else if ($mes == 'Octubre') {
    //     $numMes = '10';
    // } else if ($mes == 'Noviembre') {
    //     $numMes = '11';
    // } else if ($mes == 'Diciembre') {
    //     $numMes = '12';
    // }

    require('conexion.php');

    $FechaInicio = $numMes . '-01 00:00:00';
    $FechaFin = $numMes . '-31 23:59:59';

    $sql = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE  tipoMantencion = '$tipo' AND fechaOrden BETWEEN '$FechaInicio' AND '$FechaFin' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtieneOTTipo($depa, $tipo, $numMes)
{
    require('conexion.php');
    $FechaInicio = $numMes . '-01 00:00:00';
    $FechaFin = $numMes . '-31 23:59:59';

    $sql = "SELECT COUNT(*) as total FROM trabajos_general WHERE departamento = $depa AND tipoTrabajo = '$tipo' AND fechaTG BETWEEN '$FechaInicio' AND '$FechaFin' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtieneHTTipo($depa, $tipo, $numMes)
{

    require('conexion.php');

    $FechaInicio = $numMes . '-01 00:00:00';
    $FechaFin = $numMes . '-31 23:59:59';

    $sql = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE idDepartamento = $depa AND tipoMantencion = '$tipo' AND fechaOrden BETWEEN '$FechaInicio' AND '$FechaFin' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtieneHTSinTipo($depa, $numMes)
{

    require('conexion.php');

    $FechaInicio = $numMes . '-01 00:00:00';
    $FechaFin = $numMes . '-31 23:59:59';

    $sql = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE idDepartamento = $depa AND (tipoMantencion NOT LIKE 'MPI' AND tipoMantencion NOT LIKE 'MRI' AND tipoMantencion NOT LIKE 'MPE' AND tipoMantencion NOT LIKE 'MRE' AND tipoMantencion NOT LIKE 'OM' AND tipoMantencion NOT LIKE 'IS') AND fechaOrden BETWEEN '$FechaInicio' AND '$FechaFin' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtieneHTTipo2($depa, $tipo, $numMes)
{

    // if ($mes == 'Enero') {
    //     $numMes = '01';
    // } else if ($mes == 'Febrero') {
    //     $numMes = '02';
    // } else if ($mes == 'Marzo') {
    //     $numMes = '03';
    // } else if ($mes == 'Abril') {
    //     $numMes = '04';
    // } else if ($mes == 'Mayo') {
    //     $numMes = '05';
    // } else if ($mes == 'Junio') {
    //     $numMes = '06';
    // } else if ($mes == 'Julio') {
    //     $numMes = '07';
    // } else if ($mes == 'Agosto') {
    //     $numMes = '08';
    // } else if ($mes == 'Septiembre') {
    //     $numMes = '09';
    // } else if ($mes == 'Octubre') {
    //     $numMes = '10';
    // } else if ($mes == 'Noviembre') {
    //     $numMes = '11';
    // } else if ($mes == 'Diciembre') {
    //     $numMes = '12';
    // }

    require('conexion.php');

    $FechaInicio = $numMes . '-01 00:00:00';
    $FechaFin = $numMes . '-31 23:59:59';

    $sql = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE idDepartamento = $depa AND tipoMantencion = '$tipo' AND fechaOrden BETWEEN '$FechaInicio' AND '$FechaFin' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function contarBMS($folioTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['TOTAL'];
    }
    return $total;
}


//OBTIENE LA CANTIDAD DE HOJAS TÉCNICAS X TECNICO
function obtieneCantTecHT($tecnico, $depa, $mes)
{

    if ($mes == 'Enero') {
        $numMes = '01';
    } else if ($mes == 'Febrero') {
        $numMes = '02';
    } else if ($mes == 'Marzo') {
        $numMes = '03';
    } else if ($mes == 'Abril') {
        $numMes = '04';
    } else if ($mes == 'Mayo') {
        $numMes = '05';
    } else if ($mes == 'Junio') {
        $numMes = '06';
    } else if ($mes == 'Julio') {
        $numMes = '07';
    } else if ($mes == 'Agosto') {
        $numMes = '08';
    } else if ($mes == 'Septiembre') {
        $numMes = '09';
    } else if ($mes == 'Octubre') {
        $numMes = '10';
    } else if ($mes == 'Noviembre') {
        $numMes = '11';
    } else if ($mes == 'Diciembre') {
        $numMes = '12';
    }

    require('conexion.php');

    $FechaInicio = '2023-' . $numMes . '-01 00:00:00';
    $FechaFin = '2023-' . $numMes . '-31 23:59:59';

    $sql = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE idCreador = $tecnico AND idDepartamento = $depa AND fechaOrden BETWEEN '$FechaInicio' AND '$FechaFin' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);

    return $dato['total'];
}

//OBTIENE EL NOMBRE CREADOR DE LA HOJA TECNICA
function obtieneNomTecHT($tecnico, $rol)
{
    require('conexion.php');

    if ($rol == 'jefes_tecnicos') {
        $nomTec = 'nomJefe';
        $id = 'idJefe';
    } else if ($rol == 'personal') {
        $nomTec = 'nomPersonal';
        $id = 'idPersonal';
    }

    $sql = "SELECT " . $nomTec . "   as nomTec FROM  " . $rol . " WHERE " . $id . " = " . $tecnico;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);

    return $dato['nomTec'];
}

//OBTIENE LA CANTIDAD DE HOJAS TECNICAS x departamento
function contarHojasTecnicas($id, $mes)
{

    if ($mes == 'Enero') {
        $numMes = '01';
    } else if ($mes == 'Febrero') {
        $numMes = '02';
    } else if ($mes == 'Marzo') {
        $numMes = '03';
    } else if ($mes == 'Abril') {
        $numMes = '04';
    } else if ($mes == 'Mayo') {
        $numMes = '05';
    } else if ($mes == 'Junio') {
        $numMes = '06';
    } else if ($mes == 'Julio') {
        $numMes = '07';
    } else if ($mes == 'Agosto') {
        $numMes = '08';
    } else if ($mes == 'Septiembre') {
        $numMes = '09';
    } else if ($mes == 'Octubre') {
        $numMes = '10';
    } else if ($mes == 'Noviembre') {
        $numMes = '11';
    } else if ($mes == 'Diciembre') {
        $numMes = '12';
    }

    $FechaInicio = '2023-' . $numMes . '-01 00:00:00';
    $FechaFin = '2023-' . $numMes . '-31 23:59:59';

    include('conexion.php');

    if ($mes == 'Enero' || $mes == 'Febrero' || $mes == 'Marzo') {

        $FechaInicio = '2023-01-01 00:00:00';
        $FechaFin = '2023-03-31 23:59:59';

        $query = "SELECT COUNT(*) as total FROM  ordenes_trabajos_general WHERE idDepartamento = $id AND fechaOrden BETWEEN '$FechaInicio' AND '$FechaFin' ";
        $res = mysqli_query($conexion, $query);
    } else {
        $query = "SELECT COUNT(*) as total FROM  ordenes_trabajos_general WHERE idDepartamento = $id AND fechaOrden BETWEEN '$FechaInicio' AND '$FechaFin' ";
        $res = mysqli_query($conexion, $query);
    }

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['total'] = $row['total'];
        $total = $datos['total'];
    }

    return $total;
}


//OBTENER EL NOMBRE DEL DIA DEL MES
function get_nombre_dia($fecha)
{
    $fechats = strtotime($fecha); //pasamos a timestamp

    //el parametro w en la funcion date indica que queremos el dia de la semana
    //lo devuelve en numero 0 domingo, 1 lunes,....
    switch (date('w', $fechats)) {
        case 0:
            return "Domingo";
            break;
        case 1:
            return "Lunes";
            break;
        case 2:
            return "Martes";
            break;
        case 3:
            return "Miercoles";
            break;
        case 4:
            return "Jueves";
            break;
        case 5:
            return "Viernes";
            break;
        case 6:
            return "Sabado";
            break;
    }
}

function buscarPatenteMov($numPatente)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as total, numPatente FROM patentes_movilizacion WHERE numPatente ="' . $numPatente . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);

    // if($dato['total'] > 0){
    //   // return $dato['nomMotivo'];
    //   return 'Existe';
    // }else{
    //   // return $dato['total'];

    //   return 'No Existe';
    // }

    return $dato['total'];

    // return $query;
}

//FORMATO MONEDA CHILENA

function moneda_chilena($numero)
{
    $numero = (string)$numero;
    $puntos = floor((strlen($numero) - 1) / 3);
    $tmp = "";
    $pos = 1;
    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $tmp = $tmp . substr($numero, $i, 1);
        if ($pos % 3 == 0 && $pos != strlen($numero))
            $tmp = $tmp . ".";
        $pos = $pos + 1;
    }
    $formateado = "$ " . strrev($tmp);
    return $formateado;
}

//FORMATO MONEDA CHILENA

function moneda_chilena2($numero)
{
    $valorFinal = '$0';
    if ($numero > 0) {
        $valorFinal = '$' . number_format($numero, 0, ',', '.');
    } else {
        $valorFinal = '$0';
    }
    return $valorFinal;
}

function moneda_chilena_excel($numero)
{
    $numero = (string)$numero;
    $puntos = floor((strlen($numero) - 1) / 3);
    $tmp = "";
    $pos = 1;
    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $tmp = $tmp . substr($numero, $i, 1);
        if ($pos % 3 == 0 && $pos != strlen($numero))
            $tmp = $tmp . ".";
        $pos = $pos + 1;
    }
    $formateado = "$" . strrev($tmp);
    return $formateado;
}

function contarImagenHojaTecnica($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM imagenes_orden_general WHERE idOrden='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

/* END FUNCTION */

function contarArchivosHojasdeVida($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM archivos_hojas_vida WHERE idHoja = $id  ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarOTTecnico($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM trabajos_general WHERE tecnico1 LIKE '%$nomUsuario%' AND estadoTG !='Trabajo Terminado'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}


function contarArchivosHojasTecnicas($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM archivos_hoja_tecnica WHERE idHojaTec = $id  ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION


function contarObs($idTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM observacion_ot WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}


function contarHT1($folioTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function contarBMS1($folioTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'BMS'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function contarCA1($folioTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'CA'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function sumarconsumohbqp($anio)
{
    include('conexion.php');
    $sqlSuma = "SELECT SUM(consumoEnergia) as total FROM electricidad_administracion WHERE tipohospital = 'HBQP' AND YEAR(fechaLectura) = '$anio'";
    $resSuma = mysqli_query($conexion, $sqlSuma);
    while ($rowSuma = $resSuma->fetch_assoc()) {
        $datos = [];
        $datos['total'] = $rowSuma['total'];
        $total = $datos['total'];
    }
    return $total;
}

function sumarconsumohsmq($anio, $idCliente)
{
    include('conexion.php');
    $SQL1 = "SELECT SUM(consumoEnergia) as TotalSumaConsumo FROM electricidad_administracion WHERE nCliente = $idCliente AND YEAR(fechaDocumento) = '$anio'";
    $RES1 = mysqli_query($conexion, $SQL1);
    while ($ROW1 = $RES1->fetch_assoc()) {
        $TotalSumaConsumo = $ROW1['TotalSumaConsumo'];
        if (empty($TotalSumaConsumo)) {
            $TotalSumaConsumo = 0;
        }
    }
    return $TotalSumaConsumo;
}

function sumarmontototalhsmq($anio, $idCliente)
{
    include('conexion.php');
    $SQL = "SELECT SUM(montoTotalMes) as TotalSuma FROM electricidad_administracion WHERE nCliente = $idCliente AND YEAR(fechaDocumento) = '$anio'";
    $RES = mysqli_query($conexion, $SQL);
    while ($ROW = $RES->fetch_assoc()) {
        $TotalSuma = $ROW['TotalSuma'];
        if (empty($TotalSuma)) {
            $TotalSuma = 0;
        }
    }
    return $TotalSuma;
}

function sumatotalconsumohsmq($anio)
{
    include('conexion.php');
    $SQL2 = "SELECT SUM(consumoEnergia) as TotalSumaConsumo1 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio'";
    $RES2 = mysqli_query($conexion, $SQL2);
    while ($ROW2 = $RES2->fetch_assoc()) {
        $TotalSumaConsumo1 = $ROW2['TotalSumaConsumo1'];
        if (empty($TotalSumaConsumo1)) {
            $TotalSumaConsumo1 = 0;
        }
    }
    return $TotalSumaConsumo1;
}

function sumarconsumoaguahsmq($anio, $idCliente)
{
    include('conexion.php');
    $SQL1 = "SELECT SUM(consumoAgua) as TotalSumaConsumo FROM agua_administracion WHERE nCliente = $idCliente AND YEAR(fechaDocumento) = '$anio' ";
    $RES1 = mysqli_query($conexion, $SQL1);
    while ($ROW1 = $RES1->fetch_assoc()) {
        $TotalSumaConsumo = $ROW1['TotalSumaConsumo'];
        if (empty($TotalSumaConsumo)) {
            $TotalSumaConsumo = 0;
        }
    }
    return $TotalSumaConsumo;
}

function sumamontotalAguahsmq($anio, $idCliente)
{
    include('conexion.php');
    $SQL = "SELECT SUM(montoTotalMes) as TotalSuma FROM agua_administracion WHERE nCliente = $idCliente AND YEAR(fechaDocumento) = '$anio'";
    $RES = mysqli_query($conexion, $SQL);
    while ($ROW = $RES->fetch_assoc()) {
        $TotalSuma = $ROW['TotalSuma'];
        if (empty($TotalSuma)) {
            $TotalSuma = 0;
        }
    }
    return $TotalSuma;
}

function totalsumaconsumoagua($anio)
{
    include('conexion.php');
    $SQL2 = "SELECT SUM(consumoAgua) as TotalSumaConsumo1 FROM agua_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio'";
    $RES2 = mysqli_query($conexion, $SQL2);
    while ($ROW2 = $RES2->fetch_assoc()) {
        $TotalSumaConsumo1 = $ROW2['TotalSumaConsumo1'];
        if (empty($TotalSumaConsumo1)) {
            $TotalSumaConsumo1 = 0;
        }
    }
    return $TotalSumaConsumo1;
}

function totalsumamontototalagua($anio)
{
    include('conexion.php');
    $SQL3 = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2 FROM agua_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio'";
    $RES3 = mysqli_query($conexion, $SQL3);
    while ($ROW3 = $RES3->fetch_assoc()) {
        $TotalSumaConsumo2 = $ROW3['TotalSumaConsumo2'];
        if (empty($TotalSumaConsumo2)) {
            $TotalSumaConsumo2 = 0;
        }
    }
    return $TotalSumaConsumo2;

}

function sumamontotalhsmq($anio)
{
    include('conexion.php');
    $SQL3 = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio'";
    $RES3 = mysqli_query($conexion, $SQL3);
    while ($ROW3 = $RES3->fetch_assoc()) {
        $TotalSumaConsumo2 = $ROW3['TotalSumaConsumo2'];
        if (empty($TotalSumaConsumo2)) {
            $TotalSumaConsumo2 = 0;
        }
    }
    return $TotalSumaConsumo2;
}

function sumarcosumotodo($anio)
{
    include('conexion.php');
    $sqlSuma = "SELECT SUM(consumoEnergia) as total FROM electricidad_administracion WHERE tipohospital = 'HBQP' AND YEAR(fechaDocumento) = '$anio'";
    $resSuma = mysqli_query($conexion, $sqlSuma);
    while ($rowSuma = $resSuma->fetch_assoc()) {
        $datos = [];
        $datos['total'] = $rowSuma['total'];
        $total = $datos['total'];
    }
    return $total;
}

function sumarmontotodo($anio)
{
    include('conexion.php');
    $sqlMonto = "SELECT SUM(montoTotalMes) as totalMonto FROM electricidad_administracion WHERE tipoHospital = 'HBQP' AND YEAR(fechaDocumento) = '$anio'";
    $resMonto = mysqli_query($conexion, $sqlMonto);
    while ($rowMonto = $resMonto->fetch_assoc()) {
        $datosMonto = [];
        $datosMonto['totalMonto'] = $rowMonto['totalMonto'];
        $totalMonto = $datosMonto['totalMonto'];
    }
    return $totalMonto;
}

function sumarmontototal($anio)
{
    include('conexion.php');
    $sqlMonto = "SELECT SUM(montoTotalMes) as totalMonto FROM electricidad_administracion WHERE tipoHospital = 'HBQP' AND YEAR(fechaLectura) = '$anio'";
    $resMonto = mysqli_query($conexion, $sqlMonto);
    while ($rowMonto = $resMonto->fetch_assoc()) {
        $datosMonto = [];
        $datosMonto['totalMonto'] = $rowMonto['totalMonto'];
        $totalMonto = $datosMonto['totalMonto'];
    }
    return $totalMonto;
}

function contarCCTV1($folioTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'CCTV'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function contarLLE1($folioTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'LLE'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function contarOtros1($folioTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'OTROS'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function contarRondas1($folioTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'Rondas'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}


function concatenarBMS($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'BMS'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $datosConcatenados .= $count . '.- ' . $rowOrdenBms['trabajoRealizado'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarCA($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'CA'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $datosConcatenados .= $count . '.- ' . $rowOrdenBms['trabajoRealizado'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarRondas($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'Rondas'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $datosConcatenados .= $count . '.- ' . $rowOrdenBms['comentariosTrabajos'] . '<br>' . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarCCTV($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'CCTV'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $datosConcatenados .= $count . '.- ' . $rowOrdenBms['trabajoRealizado'] . '<br>' . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarLLE($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'LLE'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $datosConcatenados .= $count . '.- ' . $rowOrdenBms['trabajoRealizado'] . '<br>' . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarOtros($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = 'OTROS'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $datosConcatenados .= $count . '.- ' . $rowOrdenBms['trabajoRealizado'] . '<br>' . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenargeneralavance($idTG, $tecnico1)
{
    include('conexion.php');
    $sql = "SELECT * FROM avances_trabajo_general WHERE idTG=$idTG AND nomCreador='$tecnico1' AND noavance IS NULL AND detalleObs != 'NULL'";
    $res = mysqli_query($conexion, $sql);
    $datosConca = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConca .= $count . '.- ' . $row['detalleObs'] . '<br>' . '<br>';
        $count++;
    }
    return $datosConca;
}

function concatenargeneralavance1($idTG, $tecnico1)
{
    include('conexion.php');
    $sql = "SELECT * FROM avances_trabajo_general WHERE idTG=$idTG AND nomCreador='$tecnico1' AND noavance IS NULL";
    $res = mysqli_query($conexion, $sql);
    $datosConca = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConca .= $count . '.- ' . $row['detalleObs'];
        $count++;
    }
    return $datosConca;
}

function concatenargeneralobs1($idTG, $tecnico1)
{
    include('conexion.php');
    $sql = "SELECT * FROM avances_trabajo_general WHERE idTG=$idTG AND nomCreador='$tecnico1' AND noavance IS NULL";
    $res = mysqli_query($conexion, $sql);
    $datosConca = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConca .= $count . '.- ' . $row['observacion'];
        $count++;
    }
    return $datosConca;
}

function concatenargeneralobs($idTG, $tecnico1)
{
    include('conexion.php');
    $sql = "SELECT * FROM avances_trabajo_general WHERE idTG=$idTG AND nomCreador='$tecnico1' AND noavance IS NULL AND observacion != 'NULL'";
    $res = mysqli_query($conexion, $sql);
    $datosConca = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConca .= $count . '.- ' . $row['observacion'] . '<br>' . '<br>';
        $count++;
    }
    return $datosConca;
}

function concatenarhtsintipo($folioTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG' AND tipoOT = ''";
    $res = mysqli_query($conexion, $sql);
    $datosConca = '';
    while ($row = $res->fetch_assoc()) {
        $datosConca .= '° ' . $row['trabajoRealizado'] . '<br>' . '<br>';

    }
    return $datosConca;
}

function concatenarHT($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $datosConcatenados .= $count . '.- ' . $rowOrdenBms['trabajoRealizado'] . '<br>' . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function contarHT($folioTG)
{
    include('conexion.php');
    $sqlOrdenBMS = "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
    $resOrdenBMS = mysqli_query($conexion, $sqlOrdenBMS);
    $datosConcatenados = '';
    $count = 1;
    while ($rowOrdenBms = $resOrdenBMS->fetch_assoc()) {
        $total = $rowOrdenBms['TOTAL'];
    }
    return $total;
}

function getEmail($id, $tabla)
{
    include('conexion.php');

    if ($tabla == 'personal') {
        $condicion = 'idPersonal';
        $email = 'emailPersonal';
    }
    if ($tabla == 'jefe tecnico') {
        $tabla = 'jefes_tecnicos';
        $condicion = 'idJefe';
        $email = 'emailJefe';
    }
    if ($tabla == 'directivos') {
        $condicion = 'idDirectivo';
        $email = 'emailDirectivo';
    }

    $query = "SELECT * FROM " . $tabla . " WHERE " . $condicion . "= " . $id;
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {

        $datos = [];

        $datos[$email] = $row[$email];
        $email = $datos[$email];

        return $email;
    }
}

// FIN FUNCION

function getEmaiConNomUsuario($user, $rolUsuario)
{
    include('conexion.php');

    $tabla = '';

    if ($rolUsuario == 'Tecnico') {

        $tabla = 'personal';
        $condicion = 'userPersonal';
        $email = 'emailPersonal';
    } else if ($rolUsuario == 'Profesional') {

        $tabla = 'jefes_tecnicos';
        $condicion = 'userJefe';
        $email = 'emailJefe';
    } else if ($rolUsuario == 'Administrador') {

        $tabla = 'administracion';
        $condicion = 'userAdministrador';
        $email = 'emailAdministrador';
    } else if ($rolUsuario == 'Administrativo') {

        $tabla = 'adm_tecnicos';
        $condicion = 'userAdmTecnicos';
        $email = 'emailAdmTecnicos';
    } else if ($rolUsuario == 'Directivo') {

        $tabla = 'directivos';
        $condicion = 'userDirectivo';
        $email = 'emailDirectivo';
    } else if ($rolUsuario == 'Mesa Ayuda') {

        $tabla = 'mesa_ayuda';
        $condicion = 'userMesaAyuda';
        $email = 'emailMesaAyuda';
    } else if ($rolUsuario == 'Gestión Documental') {

        $tabla = 'gestion_documental';
        $condicion = 'userGestionDoc';
        $email = 'emailGestionDoc';
    } else {
        $tabla = 'No Existe';
    }


    if (empty($tabla) || $tabla == '' || $tabla == 'No Existe') {
        return "No Existe";
    } else {
        $query = "SELECT * FROM " . $tabla . " WHERE " . $condicion . "= '" . $user . "'";
        $res = mysqli_query($conexion, $query);

        while ($row = $res->fetch_assoc()) {

            $datos = array();

            $datos[$email] = $row[$email];
            $email = $datos[$email];

            return $email;
        }
    }
}

// FIN FUNCION

function compruebaAtrasoPersonal()
{
    include('conexion.php');

    $fechaActual = date('Y-m-d');

    $query = "SELECT  COUNT(*) as cantidad FROM solicitudes_arriendo_retiro WHERE fechaTermino < '" . $fechaActual . "' ";
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {

        $datos = [];

        $datos['cantidad'] = $row['cantidad'];

        $cantidad = $datos['cantidad'];
        // echo $cantidad;

        return $cantidad;
    }
}


// FIN FUNCION

function getReporte($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM reportes_generales WHERE idReporte=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idReporte'] = $id;
        $datos['nomReporte'] = $row['nomReporte'];
        $datos['turnoReporte'] = $row['turnoReporte'];
        $datos['fechaReporte'] = $row['fechaReporte'];
        $datos['folioReporte'] = $row['folioReporte'];
        $datos['nomEncargadoTurno'] = $row['nomEncargadoTurno'];
        $datos['nomFuncionariosTurno'] = $row['nomFuncionariosTurno'];
        $datos['nomEncargadoTurnoAnterior'] = $row['nomEncargadoTurnoAnterior'];
        $datos['tareasControlCentralizado'] = $row['tareasControlCentralizado'];
        $datos['tareasInstalaciones'] = $row['tareasInstalaciones'];
        $datos['tareasEquiposIndustriales'] = $row['tareasEquiposIndustriales'];
        $datos['tareasIncendiosCombustibles'] = $row['tareasIncendiosCombustibles'];
        $datos['tareasDeteccionElectrica'] = $row['tareasDeteccionElectrica'];
        $datos['tareasInfraestructura'] = $row['tareasInfraestructura'];
        $datos['pendientesCoordinacion'] = $row['pendientesCoordinacion'];
        return $datos;
    }
}

// FIN FUNCION
function getReporteElectrico($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM reportes_electricidad WHERE idReporte=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idReporte'] = $id;
        $datos['nomReporte'] = $row['nomReporte'];
        $datos['turnoReporte'] = $row['turnoReporte'];
        $datos['fechaReporte'] = $row['fechaReporte'];
        $datos['folioReporte'] = $row['folioReporte'];
        $datos['nomEncargadoTurno'] = $row['nomEncargadoTurno'];
        $datos['nomFuncionariosTurno'] = $row['nomFuncionariosTurno'];
        $datos['nomEncargadoTurnoAnterior'] = $row['nomEncargadoTurnoAnterior'];
        $datos['tareasDeteccionElectrica'] = $row['tareasDeteccionElectrica'];
        return $datos;
    }
}

// FIN FUNCION


function existeFotoOrdenGeneral($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM imagenes_orden_general WHERE idOrden=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        return 'Existe';
    } else {
        return 'No';
    }
}

function existeFotoTicket($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM imagenes_soporte WHERE idSolicitud=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        return 'Existe';
    } else {
        return 'No';
    }
}

function getOrdenTrabajo($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM ordenes_trabajos WHERE idOrden=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idOrden'] = $id;
        $datos['folioOrden'] = $row['folioOrden'];
        $datos['nomOrden'] = $row['nomOrden'];
        $datos['fechaOrden'] = $row['fechaOrden'];
        $datos['nomSolicitante'] = $row['nomSolicitante'];
        $datos['nomTecnicoRecepcionador'] = $row['nomTecnicoRecepcionador'];
        $datos['idDepartamento'] = $row['idDepartamento'];
        $datos['nomDepartamento'] = getNomDepartamento($row['idDepartamento']);
        $datos['idSubDepartamento'] = $row['idSubDepartamento'];
        $datos['nomSubDepartamento'] = '';
        if ($datos['idSubDepartamento'] == 0 || $datos['idSubDepartamento'] < 0) {
            $datos['nomSubDepartamento'] = 'Error';
        } else {
            $datos['nomSubDepartamento'] = getNomSubDepartamento($row['idSubDepartamento']);
        }
        $datos['servicioOrden'] = $row['servicioOrden'];
        $datos['recintoOrden'] = $row['recintoOrden'];
        $datos['pmaOrden'] = $row['pmaOrden'];
        $datos['coordenadasOrden'] = $row['coordenadasOrden'];
        $datos['detalleTrabajo'] = $row['detalleTrabajo'];
        $datos['comentariosTrabajos'] = $row['comentariosTrabajos'];
        $datos['recepcionistaOrden'] = $row['recepcionistaOrden'];
        $datos['ejecutorOrden'] = $row['ejecutorOrden'];
        $datos['autorizadorOrden'] = $row['autorizadorOrden'];
        return $datos;
    }
}

// FIN FUNCION
function getOrdenTrabajoGeneral($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM ordenes_trabajos_general WHERE idOrden=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idOrden'] = $id;
        $datos['folioOrden'] = $row['folioOrden'];
        $datos['nomOrden'] = $row['nomOrden'];
        $datos['fechaOrden'] = $row['fechaOrden'];
        $datos['nomSolicitante'] = $row['nomSolicitante'];
        $datos['nomTecnicoRecepcionador'] = $row['nomTecnicoRecepcionador'];
        $datos['idDepartamento'] = $row['idDepartamento'];
        $datos['nomDepartamento'] = getNomDepartamento($row['idDepartamento']);
        $datos['idSubDepartamento'] = $row['idSubDepartamento'];
        $datos['nomSubDepartamento'] = '';
        if ($datos['idSubDepartamento'] == 0 || $datos['idSubDepartamento'] < 0) {
            $datos['nomSubDepartamento'] = 'Error';
        } else {
            $datos['nomSubDepartamento'] = getNomSubDepartamento($row['idSubDepartamento']);
        }
        $datos['servicioOrden'] = $row['servicioOrden'];
        $datos['recintoOrden'] = $row['recintoOrden'];
        $datos['pmaOrden'] = $row['pmaOrden'];
        $datos['coordenadasOrden'] = $row['coordenadasOrden'];
        $datos['tipoMantencion'] = $row['tipoMantencion'];
        $datos['trabajoRealizado'] = $row['trabajoRealizado'];
        $datos['problemaPresenta'] = $row['problemaPresenta'];
        $datos['accionesRealiza'] = $row['accionesRealiza'];
        $datos['comentariosTrabajos'] = $row['comentariosTrabajos'];
        $datos['recepcionistaOrden'] = $row['recepcionistaOrden'];
        $datos['ejecutorOrden'] = $row['ejecutorOrden'];
        $datos['autorizadorOrden'] = $row['autorizadorOrden'];
        return $datos;
    }
}

// FIN FUNCION

// FIN FUNCION
function existeFotoReporte($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM imagenes WHERE idReporte=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        return 'Existe';
    } else {
        return 'No';
    }
}

// FIN FUNCION

function existeFotoOrden($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM imagenes WHERE idOrden=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        return 'Existe';
    } else {
        return 'No';
    }
}

// FIN FUNCION

function existeFotoSolicitud($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM imagenes_soporte WHERE idSolicitud=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        return 'Existe';
    } else {
        return 'No';
    }
}

// FIN FUNCION

function getCantReportes($id)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as totalReportes FROM reportes_generales WHERE idPersonal=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalReportes'];
}

// FIN FUNCION

function getCantTotalReportes()
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as total FROM reportes_generales';
    $res = mysqli_query($conexion, $query);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if ($total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}

// FIN FUNCION

function getCantTotalReportesAnio($anio)
{
    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM reportes_generales WHERE YEAR(fechaReporte) = $anio";
    $res = mysqli_query($conexion, $query);

    // Obtenemos el resultado o null si falla
    $dato = $res ? mysqli_fetch_assoc($res) : null;

    // Cerramos la conexión para liberar recursos
    mysqli_close($conexion);

    // Retornamos el valor si existe, de lo contrario 0
    return (int)($dato['total'] ?? 0);
}

function getNomPersonal($id)
{
    include('conexion.php');
    $query = 'SELECT nomPersonal FROM personal WHERE idPersonal=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomPersonal'];
}


// FIN FUNCION

function getDatosPersonal($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM personal WHERE idPersonal=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idPersonal'] = $id;
        $datos['nomPersonal'] = $row['nomPersonal'];
        $datos['cargoPersonal'] = $row['cargoPersonal'];
        $datos['emailPersonal'] = $row['emailPersonal'];
        $datos['userPersonal'] = $row['userPersonal'];
        $datos['passPersonal'] = $row['passPersonal'];
        $datos['activoPersonal'] = $row['activoPersonal'];
        $datos['departamentoPersonal'] = $row['departamentoPersonal'];
        $datos['idSubdepartamento'] = $row['idSubdepartamento'];
        return $datos;
    } else {
        return 'Error';
    }
}

// FIN FUNCION

function getDatosJefe($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM jefes_tecnicos WHERE idJefe=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idJefe'] = $id;
        $datos['nomJefe'] = $row['nomJefe'];
        $datos['cargoJefe'] = $row['cargoJefe'];
        $datos['userJefe'] = $row['userJefe'];
        $datos['passJefe'] = $row['passJefe'];
        $datos['emailJefe'] = $row['emailJefe'];
        $datos['idDepartamento'] = $row['idDepartamento'];
        $datos['idSubdepartamento'] = $row['idSubdepartamento'];
        return $datos;
    }
}

// FIN FUNCION

function getDatosDirectivo($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM directivos WHERE idDirectivo=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idDirectivo'] = $id;
        $datos['nomDirectivo'] = $row['nomDirectivo'];
        $datos['userDirectivo'] = $row['userDirectivo'];
        $datos['passDirectivo'] = $row['passDirectivo'];
        $datos['cargoDirectivo'] = $row['cargoDirectivo'];
        $datos['emailDirectivo'] = $row['emailDirectivo'];
        return $datos;
    }
}

// FIN FUNCION

function existePersonal($user)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM personal WHERE userPersonal='" . $user . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function existeUsuarioTicket($rut)
{
    include('conexion.php');
    $query = "SELECT * FROM usuarios_tickets WHERE rutUsuariosT LIKE '" . $rut . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutUsuariosT'];
}

// FIN FUNCION

function existeJefe($jefe)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM jefes_tecnicos WHERE userJefe='" . $jefe . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeAdministrador($user)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM administracion WHERE userAdministrador='" . $user . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeAdmTecnicos($user)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM adm_tecnicos WHERE userAdmTecnicos='" . $user . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeDirectivo($user)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM directivos WHERE userDirectivo='" . $user . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeMesaAyuda($user)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM mesa_ayuda WHERE userMesaAyuda='" . $user . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeGestDoc($user)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM gestion_documental WHERE userGestionDoc='" . $user . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function getNomDepartamento($id)
{
    include('conexion.php');
    $query = 'SELECT nomDepartamento FROM departamentos WHERE idDepartamento=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomDepartamento'];
}

// FIN FUNCION

function getCargoPersonal($id)
{
    include('conexion.php');
    $query = 'SELECT cargoPersonal FROM personal WHERE idPersonal=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cargoPersonal'];
}

// FIN FUNCION

function getNomSubDepartamento($id)
{
    include('conexion.php');
    $query = 'SELECT nomSubDepartamento FROM subdepartamentos WHERE idSubDepartamento=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomSubDepartamento'];
}

// FIN FUNCION

function fechaEspañol($FechaStamp)
{
    $ano = date('Y', $FechaStamp);
    $mes = date('n', $FechaStamp);
    $dia = date('d', $FechaStamp);
    $diasemana = date('w', $FechaStamp);
    $diassemanaN = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return $diassemanaN[$diasemana] . " $dia de " . $mesesN[$mes] . " del $ano";
}

function fechaEspañol2($FechaStamp)
{
    $ano = date('Y', $FechaStamp);
    $mes = date('n', $FechaStamp);
    $dia = date('d', $FechaStamp);
    $mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return " $dia de " . $mesesN[$mes] . " de $ano";
}

function fechaEspañol3($FechaStamp)
{
    $ano = date('Y', $FechaStamp);
    $mes = date('n', $FechaStamp);
    $dia = date('d', $FechaStamp);
    $mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return $mesesN[$mes] . " de $ano";
}


function fechaEspañolMinimizado($FechaStamp)
{
    $ano = date('Y', $FechaStamp);
    $mes = date('n', $FechaStamp);
    $dia = date('d', $FechaStamp);
    $hora = date('H:i', $FechaStamp);
    $mesesN = array(1 => "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
    return " $dia " . $mesesN[$mes] . " $ano a las $hora hrs.";
}


function fechaEspañolMinimizadoSinHoras($FechaStamp)
{
    $ano = date('Y', $FechaStamp);
    $mes = date('n', $FechaStamp);
    $dia = date('d', $FechaStamp);
    $mesesN = array(1 => "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
    return " $dia " . $mesesN[$mes] . " $ano";
}

function fechaEspañolLugar($FechaStamp)
{
    $ano = date('Y', $FechaStamp);
    $mes = date('n', $FechaStamp);
    $dia = date('d', $FechaStamp);
    $mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return "Quillota, $dia de " . $mesesN[$mes] . " de $ano";
}


function mesEspañol($FechaStamp)
{
    $ano = date('Y', $FechaStamp);
    $mes = date('n', $FechaStamp);
    $mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return $mesesN[$mes] . " $ano";
}

function mesEspañol2($num)
{
    $mesesN = [
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre"
    ];

    // Retorna el mes o una cadena vacía si el número no es válido
    return $mesesN[intval($num)] ?? '';
}

function mesEspañol3($num)
{
    $mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return substr($mesesN[$num], 0, 3);
}


function nombreMesANumero($mesNombre)
{
    $mesesN = array(
        "Enero" => 1,
        "Febrero" => 2,
        "Marzo" => 3,
        "Abril" => 4,
        "Mayo" => 5,
        "Junio" => 6,
        "Julio" => 7,
        "Agosto" => 8,
        "Septiembre" => 9,
        "Octubre" => 10,
        "Noviembre" => 11,
        "Diciembre" => 12
    );

    // Convertir el nombre del mes a "Título" por si está en minúsculas o mayúsculas.
    $mesNombre = ucfirst(strtolower($mesNombre));

    return isset($mesesN[$mesNombre]) ? $mesesN[$mesNombre] : null;
}

function eliminarCero($numero)
{
    $cadena = (string)$numero; // Convertir el número en una cadena
    if ($cadena[0] === '0') { // Verificar si el primer carácter es '0'
        return substr($cadena, 1); // Devolver la subcadena sin el primer carácter
    }
    return $cadena; // Devolver el número original si no hay un '0' al principio
}

function contarImagen($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM imagenes WHERE idReporte=" . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarArchivosTrabajoGeneral($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM Archivos_Trabajo_General_Tecnico WHERE idTG = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

// FIN FUNCION

function contarImagenSolicitud($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM imagenes_soporte WHERE idSolicitud='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function getRutaImagen($id)
{
    include('conexion.php');
    $query = "SELECT rutaImagen FROM imagenes WHERE idImagen='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaImagen'];
}

// FIN FUNCION

function getReporteImagen($id)
{
    include('conexion.php');
    $query = "SELECT idReporte FROM imagenes WHERE idImagen='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idReporte'];
}

// FIN FUNCION

function contarMateriales($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM material_orden WHERE idOrden='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function contarMaterialesSolicitud($id)
{
    include('conexion.php');
    $query = "SELECT count(*) AS total FROM material_solicompra_tickets_pm msc INNER JOIN solicitudes_compra_tickets_pm sc ON msc.idSoliCompra = sc.idSoliCompra WHERE sc.idSolicitudSoporte='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getNomServicio($id)
{
    include('conexion.php');
    $query = "SELECT nomServicio FROM servicios WHERE idServicio='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomServicio'];
}

// FIN FUNCION


function contarImagenOrden($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM imagenes WHERE idOrden='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function buscarTipoArchivo($nomTipo)
{
    include('conexion.php');
    $query = "SELECT nomTipo  FROM tiposarchivos WHERE nomTipo LIKE '" . $nomTipo . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomTipo'];
}

// FIN FUNCION

function buscarServicio($nomServicio)
{
    include('conexion.php');
    $query = "SELECT idServicio FROM servicios WHERE nomServicio LIKE '" . $nomServicio . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idServicio'];
}

// FIN FUNCION

function getNomCategoria($id)
{
    include('conexion.php');
    $query = "SELECT nomCategoria FROM categorias WHERE idCategoria='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomCategoria'];
}

// FIN FUNCION

function getNomSubCategoria($id)
{
    include('conexion.php');
    $query = "SELECT nomSubcategoria FROM subcategorias WHERE idSubcategoria='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomSubcategoria'];
}

// FIN FUNCION

function getSubCategoriaEquipo($id)
{
    include('conexion.php');
    $query = "SELECT idSubcategoria FROM equipos WHERE idEquipo ='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idSubcategoria'];
}

// FIN FUNCION

function getNomEquipo($id)
{
    include('conexion.php');
    $query = "SELECT nomEquipo FROM equipos WHERE idEquipo='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomEquipo'];
}

// FIN FUNCION

function getCategoriaEquipo($id)
{
    include('conexion.php');
    $query = "SELECT e.idEquipo AS idEquipo, e.nomEquipo AS nomEquipo, c.idCategoria AS idCategoria, c.nomCategoria AS nomCategoria FROM categorias c INNER JOIN subcategorias sc ON c.idCategoria = sc.idCategoria INNER JOIN equipos e ON sc.idSubcategoria = e.idSubCategoria WHERE e.idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (is_array($dato)) {
        return $dato['nomCategoria'];
    }
}

// FIN FUNCION

function getSubCategoriaConEquipo($id)
{
    include('conexion.php');
    $query = "SELECT e.idEquipo AS idEquipo, e.nomEquipo AS nomEquipo, sc.nomSubCategoria AS nomSubCategoria, c.idCategoria AS idCategoria, c.nomCategoria AS nomCategoria FROM categorias c INNER JOIN subcategorias sc ON c.idCategoria = sc.idCategoria INNER JOIN equipos e ON sc.idSubcategoria = e.idSubCategoria WHERE e.idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (is_array($dato)) {
        return $dato['nomSubCategoria'] . ' / ' . $dato['nomEquipo'];
    }
}

// FIN FUNCION

function getCategoriaSegunSubcategoria($id)
{
    include('conexion.php');
    $query = "SELECT idCategoria FROM subcategorias WHERE idSubcategoria='" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCategoria'];
}

// FIN FUNCION

function getStatusForzado($id)
{
    include('conexion.php');
    $query = "SELECT forzadoEquipo FROM equipos WHERE idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['forzadoEquipo'];
}

// FIN FUNCION

function getStatusForzadoEncendido($id)
{
    include('conexion.php');
    $query = "SELECT forzadoEncendido FROM equipos WHERE idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['forzadoEncendido'];
}

// FIN FUNCION

function getStatusForzadoApagado($id)
{
    include('conexion.php');
    $query = "SELECT forzadoApagado FROM equipos WHERE idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['forzadoApagado'];
}

// FIN FUNCION

function getStatusForzadoVariables($id)
{
    include('conexion.php');
    $query = "SELECT forzadoVariable FROM equipos WHERE idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['forzadoVariable'];
}

// FIN FUNCION

function getStatusAutomatico($id)
{
    include('conexion.php');
    $query = "SELECT modoAutomatico FROM equipos WHERE idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['modoAutomatico'];
}

// FIN FUNCION

function contarEquiposForzados()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM equipos WHERE forzadoEquipo='1'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getUltimoRegistroForzado($id)
{
    include('conexion.php');
    $query = 'SELECT idEquipo, detalleObservaciones, fechaObservaciones, personalObservacion, esForzado FROM equipos_observaciones WHERE idEquipo = ' . $id . ' AND esForzado NOT LIKE "%Observación Normal%" AND esForzado NOT LIKE "%Modo Automatico%" ORDER BY fechaObservaciones DESC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idEquipo'] = $row['idEquipo'];
        $datos['detalleObservaciones'] = $row['detalleObservaciones'];
        $datos['fechaObservaciones'] = $row['fechaObservaciones'];
        $datos['personalObservacion'] = $row['personalObservacion'];
        $datos['esForzado'] = $row['esForzado'];
        return $datos;
    }
}

// FIN FUNCION

function contarPersonal($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM personal WHERE departamentoPersonal=" . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarJefe($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM jefes_tecnicos WHERE idDepartamento=" . $id;

    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function existeSubcategoria($subcategoria, $idCategoria)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM subcategorias WHERE nomSubcategoria LIKE '" . $subcategoria . "' AND idCategoria = '" . $idCategoria . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeEquipo($nomEquipo, $categoriaAgregar)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM equipos WHERE nomEquipo LIKE '" . $nomEquipo . "' AND idSubCategoria = '" . $categoriaAgregar . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function contarSubCategoria($idCategoria)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM subcategorias WHERE idCategoria = '" . $idCategoria . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function contarEquipos($idSubCategoria)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM equipos WHERE idSubCategoria = '" . $idSubCategoria . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function getActa($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM actas WHERE idActa=' . $id . '';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idActa'] = $id;
        $datos['folioActa'] = $row['folioActa'];
        $datos['fechaActa'] = $row['fechaActa'];
        $datos['rutTarjeta'] = $row['rutTarjeta'];
        $datos['codigoTarjeta'] = $row['codigoTarjeta'];
        $datos['nomTarjeta'] = $row['nomTarjeta'];
        $datos['unidadTarjeta'] = $row['unidadTarjeta'];
        $datos['nomSolicitante'] = $row['nomSolicitante'];
        $datos['nomControlCentralizado'] = $row['nomControlCentralizado'];
        $datos['calidadContractual'] = $row['calidadContractual'];
        $datos['numTelefono'] = $row['numTelefono'];
        $datos['tipoActa'] = $row['tipoActa'];
        $datos['personaDiscapacitada'] = $row['personaDiscapacitada'];
        $datos['personaBecada'] = $row['personaBecada'];
        return $datos;
    }
}

// FIN FUNCION

function getCreadorReporte($id)
{
    include('conexion.php');
    $query = "SELECT nomEncargadoTurno FROM reportes_generales WHERE idReporte = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomEncargadoTurno'];
}

function contarObservacionesSubCategoria($idSubCategoria)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM bitacora WHERE idSubCategoria = '" . $idSubCategoria . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function contarObservaciones()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM bitacora";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerIDBitacora()
{
    include('conexion.php');
    $query = "SELECT idCategoria FROM categorias WHERE nomCategoria LIKE 'bitácora'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $idCategoria = $dato['idCategoria'];
    if ($idCategoria > 0) {
        $datoMandar = $idCategoria;
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}

// FIN FUNCION

function getNomJefeTecnico($id)
{
    include('conexion.php');
    $query = 'SELECT nomJefe FROM jefes_tecnicos WHERE idJefe =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomJefe'];
}

function getCargoJefeTecnico($id)
{
    include('conexion.php');
    $sql = "SELECT cargoJefe FROM jefes_tecnicos WHERE idJefe = $id";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['cargoJefe'])) {
            $cargoJefe = $dato['cargoJefe'];
            $cargoJefe = trim($cargoJefe);
            if (!empty($cargoJefe)) {
                $datoMandar = $cargoJefe;
            } else {
                $datoMandar = 'Error';
            }
        } else {
            $datoMandar = 'Error';
        }
    } else {
        $datoMandar = 'Error';
    }

    return $datoMandar;
}

function getNomDirectivo($id)
{
    include('conexion.php');
    $query = 'SELECT nomDirectivo FROM directivos WHERE idDirectivo  =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomDirectivo'];
}

function getNomAdministrador($id)
{
    include('conexion.php');
    $query = 'SELECT nomAdministrador FROM administracion WHERE idAdministrador =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomAdministrador'];
}

function getRolAdministrador($id)
{
    include('conexion.php');
    $query = 'SELECT rolAdministrador FROM administracion WHERE idAdministrador =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rolAdministrador'];
}

function obtenerFolioReporte($id)
{
    include('conexion.php');
    $query = 'SELECT folioReporte FROM reportes_generales WHERE idReporte =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['folioReporte'];
}

function obtenerNombreReporte($id)
{
    include('conexion.php');
    $query = 'SELECT nomReporte FROM reportes_generales WHERE idReporte =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomReporte'];
}

function getDownloadBitacora($id)
{
    include('conexion.php');
    $query = 'SELECT idDescargaB, fechaDescargaB, usuarioDescargaB, tipoDescargaB,comentarioDescargaB FROM descargas_bitacora WHERE idDescargaB = ' . $id . ' LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idDescargaB'] = $row['idDescargaB'];
        $datos['fechaDescargaB'] = $row['fechaDescargaB'];
        $datos['usuarioDescargaB'] = $row['usuarioDescargaB'];
        $datos['tipoDescargaB'] = $row['tipoDescargaB'];
        $datos['comentarioDescargaB'] = $row['comentarioDescargaB'];
        return $datos;
    }
}

// FIN FUNCION

function obtenerFechaInicio()
{
    include('conexion.php');
    $query = 'SELECT fechaBitacora FROM bitacora ORDER BY fechaBitacora ASC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['fechaBitacora'];
}

function obtenerFechaTermino()
{
    include('conexion.php');
    $query = 'SELECT fechaBitacora FROM bitacora ORDER BY fechaBitacora DESC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['fechaBitacora'];
}

function revisarListadoBitacora()
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM bitacora ORDER BY fechaBitacora ASC';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function revisarListadoBitacoraFechas($fechaInicio, $fechaTermino)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM bitacora WHERE fechaBitacora BETWEEN "' . $fechaInicio . '" AND "' . $fechaTermino . '" ORDER BY fechaBitacora ASC';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerNombreRutCA($rut)
{
    include('conexion.php');
    $query = 'SELECT nomTarjeta FROM actas WHERE rutTarjeta = "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if ($dato && $dato["nomTarjeta"] == !'') {
        return $dato['nomTarjeta'];
    } else {
        return "error";
    }
}


function obtenerTelefonoRutCA($rut)
{
    include('conexion.php');
    $query = 'SELECT numTelefono FROM actas WHERE rutTarjeta = "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if ($dato && $dato["numTelefono"] == !'') {
        return $dato['numTelefono'];
    } else {
        return "Sin Información";
    }
}


function obtenerUnidadRutCA($rut)
{
    include('conexion.php');
    $query = 'SELECT unidadTarjeta FROM actas WHERE rutTarjeta = "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if ($dato && $dato["unidadTarjeta"] == !'') {
        return $dato['unidadTarjeta'];
    } else {
        return "Sin Información";
    }
}

function getIDInstrumentacion()
{
    include('conexion.php');
    $query = 'SELECT idDepartamento FROM departamentos WHERE nomDepartamento LIKE "Instrumentación"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idDepartamento'];
}

function getIDControlCentra()
{
    include('conexion.php');
    $query = 'SELECT idDepartamento FROM departamentos WHERE nomDepartamento LIKE "Control Centralizado"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idDepartamento'];
}

function getIDDepartamento($nomDepartamento)
{
    include('conexion.php');
    $query = 'SELECT idDepartamento FROM departamentos WHERE nomDepartamento LIKE "' . $nomDepartamento . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idDepartamento'];
}

function buscarPatente($patente)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM parking WHERE patenteParking LIKE "' . $patente . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function contarPatente($rut)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM parking WHERE rutParking LIKE "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function buscarConductor($nomConductor)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as total, nomConductor FROM conductores_movilizacion WHERE nomConductor ="' . $nomConductor . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);

    // if($dato['total'] > 0){
    //   // return $dato['nomMotivo'];
    //   return 'Existe';
    // }else{
    //   // return $dato['total'];

    //   return 'No Existe';
    // }

    return $dato['total'];

    // return $query;
}

function buscarConductor2($nomConductor)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as total, nomConductor FROM conductores_movilizacion WHERE nomConductor ="' . $nomConductor . '" AND antesConductor = 2';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);

    // if($dato['total'] > 0){
    //   // return $dato['nomMotivo'];
    //   return 'Existe';
    // }else{
    //   // return $dato['total'];

    //   return 'No Existe';
    // }

    return $dato['total'];

    // return $query;
}

function buscarUnidad($nomUnidad)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as total, nomUnidad FROM unidad_movilizacion WHERE nomUnidad ="' . $nomUnidad . '"';
    echo $query;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);

    // if($dato['total'] > 0){
    //   // return $dato['nomMotivo'];
    //   return 'Existe';
    // }else{
    //   // return $dato['total'];

    //   return 'No Existe';
    // }

    return $dato['total'];

    // return $query;
}


function buscarCCAA($rut)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM control_acceso WHERE work_id LIKE "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getDatosCCAA($work_id)
{
    include('conexion.php');
    $query = 'SELECT idControlAcceso, name, card_number, card_type, department FROM control_acceso WHERE work_id = ' . $work_id . ' LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idControlAcceso'] = $row['idControlAcceso'];
        $datos['name'] = $row['name'];
        $datos['card_number'] = $row['card_number'];
        $datos['card_type'] = $row['card_type'];
        $datos['department'] = $row['department'];
        return $datos;
    }
}

// FIN FUNCION

function existeAleatorio($aleatorioSolicitud)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE aleatorioSolicitud LIKE '" . $aleatorioSolicitud . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeAleatorioPassword($aleatorio)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM password_recovery WHERE aleatorioPassReco LIKE '" . $aleatorio . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function consultarEstado($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT situacionSolicitud FROM solicitudes_soportes WHERE folioSolicitud LIKE "' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['situacionSolicitud'];
}

function contarConsultaEstado($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM solicitudes_soportes WHERE folioSolicitud LIKE "' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarSolicitudes()
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM solicitudes_soportes';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getSolicitudSoporte($idSolicitud)
{
    include('conexion.php');
    $query = 'SELECT * FROM solicitudes_soportes WHERE idSolicitud = ' . $idSolicitud;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idSolicitud'] = $row['idSolicitud'];
        $datos['folioSolicitud'] = $row['folioSolicitud'];
        $datos['aleatorioSolicitud'] = $row['aleatorioSolicitud'];
        $datos['nomSolicitante'] = $row['nomSolicitante'];
        $datos['rutSolicitante'] = $row['rutSolicitante'];
        $datos['deptoSolicitante'] = $row['deptoSolicitante'];
        $datos['emailSolicitante'] = $row['emailSolicitante'];
        $datos['telefonoSolicitante'] = $row['telefonoSolicitante'];
        $datos['ubicacionSolicitud'] = $row['ubicacionSolicitud'];
        $datos['deptoResponsable'] = $row['deptoResponsable'];
        $datos['tipoProblemaSolicitud'] = $row['tipoProblemaSolicitud'];
        $datos['problemaSolicitud'] = $row['problemaSolicitud'];
        $datos['conclusionSolicitud'] = $row['conclusionSolicitud'];
        $datos['tecnicoSolicitud'] = $row['tecnicoSolicitud'];
        $datos['inicioSolicitud'] = $row['inicioSolicitud'];
        $datos['terminoSolicitud'] = $row['terminoSolicitud'];
        $datos['situacionSolicitud'] = $row['situacionSolicitud'];
        $datos['canceladaSolicitud'] = $row['canceladaSolicitud'];
        $datos['finalizadaSolicitud'] = $row['finalizadaSolicitud'];
        $datos['nuevaObservacion'] = $row['nuevaObservacion'];
        return $datos;
    }
}

// FIN FUNCION

function getNomTipoSolicitud($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT nomTipoSolicitud FROM solicitudes_tipos WHERE idTipoSolicitud=' . $folioSolicitud . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomTipoSolicitud'];
}

// FIN FUNCION

function getNomPersonalSolicitud($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT tecnicoSolicitud FROM solicitudes_soportes WHERE folioSolicitud="' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['tecnicoSolicitud'];
}

// FIN FUNCION

function getNomDeptoSolicitud($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT deptoResponsable FROM solicitudes_soportes WHERE folioSolicitud="' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['deptoResponsable'];
}

// FIN FUNCION

function getTerminoSolicitud($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT terminoSolicitud FROM solicitudes_soportes WHERE folioSolicitud="' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['terminoSolicitud'];
}

// FIN FUNCION

function getDetalleTicketProyecto($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT detalleTicketProyecto FROM solicitudes_soportes WHERE folioSolicitud="' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['detalleTicketProyecto'];
}

// FIN FUNCION

function getDetalleObservaSolicitudes($tipoObservaSolicitud, $idSolicitud)
{
    include('conexion.php');
    $query = 'SELECT detalleObservaSolicitudes FROM observaciones_tickets WHERE tipoObservaSolicitudes="' . $tipoObservaSolicitud . '" AND idSolicitud=' . $idSolicitud . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['detalleObservaSolicitudes'];
}

// FIN FUNCION

function getIDxFolio($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT idSolicitud FROM solicitudes_soportes WHERE folioSolicitud="' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idSolicitud'];
}

// FIN FUNCION

function getEmailSolicitud($idSolicitud)
{
    include('conexion.php');
    $query = 'SELECT emailSolicitante FROM solicitudes_soportes WHERE idSolicitud=' . $idSolicitud . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['emailSolicitante'];
}

// FIN FUNCION

function getNomSolicitanteTicket($idSolicitud)
{
    include('conexion.php');
    $query = 'SELECT nomSolicitante FROM solicitudes_soportes WHERE idSolicitud=' . $idSolicitud . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomSolicitante'];
}

// FIN FUNCION

function getRazonesTerminoSolicitud($folioSolicitud)
{
    include('conexion.php');
    $query = 'SELECT conclusionSolicitud FROM solicitudes_soportes WHERE folioSolicitud="' . $folioSolicitud . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['conclusionSolicitud'];
}

// FIN FUNCION


function getDeptoResponsableSolicitud($id)
{
    include('conexion.php');
    $query = 'SELECT deptoResponsable FROM solicitudes_soportes WHERE idSolicitud =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['deptoResponsable'];
}

// FIN FUNCION


function getFolioSolicitud($id)
{
    include('conexion.php');
    $query = 'SELECT folioSolicitud FROM solicitudes_soportes WHERE idSolicitud =' . $id . '';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['folioSolicitud'];
}

// FIN FUNCION

function obtenerSolicitudesCreadas()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE situacionSolicitud LIKE 'Solicitud Creada'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION


function obtenerSolicitudesAdministrativo($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE finalizadaSolicitud = 0 AND canceladaSolicitud = 0 AND tecnicoSolicitud LIKE '%" . $nomUsuario . "%'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION


function obtenerSolicitudesSegunNombre($nomPersonal)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE finalizadaSolicitud = 0 AND canceladaSolicitud = 0 AND tecnicoSolicitud LIKE '%" . $nomPersonal . "%'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function obtenerOTSegunNombre($nomPersonal)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM trabajos_general WHERE estadoTG = 'Trabajo Ingresado' AND tecnico1 LIKE '%" . $nomPersonal . "%'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerHistorialDocu($nomPersonal)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM recepcion_documento WHERE estado NOT LIKE 'Finalizado' AND asignadoRecepcion LIKE'%" . $nomPersonal . "%'";
    $res = mysqli_query($conexion, $query);
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);
        mysqli_close($conexion);
        $cantidadDocumento = $dato['total'];
        if ($cantidadDocumento > 0) {
            $paso = 'paso';
        } else {
            $cantidadDocumento = 0;
        }
    } else {
        $cantidadDocumento = 0;
    }
    return $cantidadDocumento;

}


function obtenerSolicitudesSegunNombreJefe($nomPersonal)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE finalizadaSolicitud = 0 AND canceladaSolicitud = 0 AND tecnicoSolicitud LIKE '%" . $nomPersonal . " (Jefe %'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerSolicitudesSegunNombreProfesional($nomPersonal)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE finalizadaSolicitud = 0 AND canceladaSolicitud = 0 AND tecnicoSolicitud LIKE '%" . $nomPersonal . " (Profesional %'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerSolicitudesSegunNombreTecnico($nomPersonal)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE finalizadaSolicitud = 0 AND canceladaSolicitud = 0 AND tecnicoSolicitud LIKE '%" . $nomPersonal . " (Técnico %'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerSolicitudesSegunNombreAuxiliar($nomPersonal)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE finalizadaSolicitud = 0 AND canceladaSolicitud = 0 AND tecnicoSolicitud LIKE '%" . $nomPersonal . " (Auxiliar %'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerSolicitudesSegunDepto($nomDepartamento)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE finalizadaSolicitud = 0 AND canceladaSolicitud = 0 AND deptoResponsable LIKE '%" . $nomDepartamento . "%'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION


function revisarHistoricoEquiposID($id)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM equipos WHERE idSubCategoria = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getDescargaHistorico($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM descargas_historico_sistemas WHERE idDescargaH = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idDescargaH'] = $row['idDescargaH'];
        $datos['fechaDescargaH'] = $row['fechaDescargaH'];
        $datos['usuarioDescargaH'] = $row['usuarioDescargaH'];
        $datos['tipoDescargaH'] = $row['tipoDescargaH'];
        $datos['comentarioDescargaH'] = $row['comentarioDescargaH'];
        $datos['idSubCategoria'] = $row['idSubCategoria'];
        return $datos;
    }
}


function getEquiposSubCategoria($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM equipos WHERE idSubCategoria = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idEquipo'] = $row['idEquipo'];
        $datos['nomEquipo'] = $row['nomEquipo'];
        $datos['idSubCategoria'] = $row['idSubCategoria'];
        $datos['forzadoEquipo'] = $row['forzadoEquipo'];
        $datos['forzadoEncendido'] = $row['forzadoEncendido'];
        $datos['forzadoApagado'] = $row['forzadoApagado'];
        $datos['forzadoVariable'] = $row['forzadoVariable'];
        $datos['modoAutomatico'] = $row['modoAutomatico'];
        return $datos;
    }
}

function time_elapsed_string($ptime)
{
    $time_ago = strtotime($ptime);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "Recientemente";
    } //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "Hace 1 minuto.";
        } else {
            return "Hace $minutes minutos.";
        }
    } //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "Hace 1 hora.";
        } else {
            return "Hace $hours horas.";
        }
    } //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Ayer.";
        } else {
            return "Hace $days d&iacute;as.";
        }
    } //Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "Hace 1 mes.";
        } else {
            return "Hace $weeks meses.";
        }
    } //Months
    else if ($months <= 12) {
        if ($months == 1) {
            return "Hace 1 mes.";
        } else {
            return "Hace $months meses.";
        }
    } //Years
    else {
        if ($years == 1) {
            return "Hace 1 año.";
        } else {
            return "Hace $years años.";
        }
    }
}

//FIN FUNCION

function getDatosAdministrativo($idAdmTecnicos)
{
    include('conexion.php');
    $query = 'SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = ' . $idAdmTecnicos;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idAdmTecnicos'] = $row['idAdmTecnicos'];
        $datos['nomAdmTecnicos'] = $row['nomAdmTecnicos'];
        $datos['userAdmTecnicos'] = $row['userAdmTecnicos'];
        $datos['passAdmTecnicos'] = $row['passAdmTecnicos'];
        $datos['emailAdmTecnicos'] = $row['emailAdmTecnicos'];
        return $datos;
    }
}

// FIN FUNCION

function getSoliCompra_ticket_pm($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM solicitudes_compra_tickets_pm WHERE idSoliCompra = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idSoliCompra'] = $row['idSoliCompra'];
        $datos['numSoliCompra'] = $row['numSoliCompra'];
        $datos['folioSoliCompra'] = $row['folioSoliCompra'];
        $datos['fechaSoliCompra'] = $row['fechaSoliCompra'];
        $datos['personalSoliCompra'] = $row['personalSoliCompra'];
        $datos['unidadSoliCompra'] = $row['unidadSoliCompra'];
        $datos['emailSoliCompra'] = $row['emailSoliCompra'];
        $datos['argumentosSoliCompra'] = $row['argumentosSoliCompra'];
        $datos['adjuntaCotizacion'] = $row['adjuntaCotizacion'];
        $datos['estadoSoliCompra'] = $row['estadoSoliCompra'];
        $datos['idSolicitudSoporte'] = $row['idSolicitudSoporte'];
        return $datos;
    }
}

// FIN FUNCION

function getSoliCompra_pm($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM solicitudes_compra_pm WHERE idSoliCompra = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idSoliCompra'] = $row['idSoliCompra'];
        $datos['numSoliCompra'] = $row['numSoliCompra'];
        $datos['folioSoliCompra'] = $row['folioSoliCompra'];
        $datos['fechaSoliCompra'] = $row['fechaSoliCompra'];
        $datos['personalSoliCompra'] = $row['personalSoliCompra'];
        $datos['unidadSoliCompra'] = $row['unidadSoliCompra'];
        $datos['emailSoliCompra'] = $row['emailSoliCompra'];
        $datos['argumentosSoliCompra'] = $row['argumentosSoliCompra'];
        $datos['adjuntaCotizacion'] = $row['adjuntaCotizacion'];
        $datos['estadoSoliCompra'] = $row['estadoSoliCompra'];
        return $datos;
    }
}

// FIN FUNCION

function getSoliCompra_sdo($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM solicitudes_compra_sdo WHERE idSoliCompra = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idSoliCompra'] = $row['idSoliCompra'];
        $datos['numSoliCompra'] = $row['numSoliCompra'];
        $datos['folioSoliCompra'] = $row['folioSoliCompra'];
        $datos['fechaSoliCompra'] = $row['fechaSoliCompra'];
        $datos['personalSoliCompra'] = $row['personalSoliCompra'];
        $datos['unidadSoliCompra'] = $row['unidadSoliCompra'];
        $datos['emailSoliCompra'] = $row['emailSoliCompra'];
        $datos['argumentosSoliCompra'] = $row['argumentosSoliCompra'];
        $datos['adjuntaCotizacion'] = $row['adjuntaCotizacion'];
        $datos['estadoSoliCompra'] = $row['estadoSoliCompra'];
        $datos['isCotizacion'] = $row['isCotizacion'];
        $datos['isRevisado'] = $row['isRevisado'];
        $datos['idSolicitudSoporte'] = $row['idSolicitudSoporte'];
        $datos['idSubAsigna'] = $row['idSubAsigna'];
        $datos['scSigh'] = $row['scSigh'];
        return $datos;
    }
}

// FIN FUNCION
function getSoliCompra_sdo_cc($id)
{
    include('conexion.php');
    $query = "SELECT * FROM solicitudes_compra_sdo_cc WHERE idSoliCompra = $id";
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idSoliCompra'] = $row['idSoliCompra'];
        $datos['numSoliCompra'] = $row['numSoliCompra'];
        $datos['folioSoliCompra'] = $row['folioSoliCompra'];
        $datos['fechaSoliCompra'] = $row['fechaSoliCompra'];
        $datos['personalSoliCompra'] = $row['personalSoliCompra'];
        $datos['unidadSoliCompra'] = $row['unidadSoliCompra'];
        $datos['emailSoliCompra'] = $row['emailSoliCompra'];
        $datos['argumentosSoliCompra'] = $row['argumentosSoliCompra'];
        $datos['adjuntaCotizacion'] = $row['adjuntaCotizacion'];
        $datos['estadoSoliCompra'] = $row['estadoSoliCompra'];
        $datos['isCotizacion'] = $row['isCotizacion'];
        $datos['isRevisado'] = $row['isRevisado'];
        $datos['idSolicitudSoporte'] = $row['idSolicitudSoporte'];
        $datos['idSubAsigna'] = $row['idSubAsigna'];
        return $datos;
    }
}

function contarMaterialesSC($id)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM material_solicompra_tickets_pm WHERE idSoliCompra = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarMaterialesSCPM($id)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM material_solicompra_pm WHERE idSoliCompra = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarMaterialesSCSDO($id)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM material_solicompra_sdo WHERE idSoliCompra = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarDescripcion($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM solicitudes_compra_pm WHERE idSoliCompra = $id AND descripcionServicio <> ''";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarSolicitudComprasSC($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_compra_sdo WHERE idSolicitudSoporte = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarSolicitudComprasPM($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_compra_pm WHERE idSolicitudSoporte = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function getNuevaObservacionSolicitud($id)
{
    include('conexion.php');
    $query = "SELECT nuevaObservacion FROM solicitudes_soportes WHERE idSolicitud = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nuevaObservacion'];
}


// FIN FUNCION

function existeNumInterno($numSoliCompra)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_compra_tickets_pm WHERE numSoliCompra = " . $numSoliCompra;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeNumInternoPM($numSoliCompra)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_compra_pm WHERE numSoliCompra = " . $numSoliCompra;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function existeNumInternoSDO($numSoliCompra)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_compra_sdo WHERE numSoliCompra = " . $numSoliCompra;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function getIniciales($nombre)
{
    $nombre = eliminar_acentos($nombre);

    $nombre = explode(' (', $nombre);

    $name = '';
    $explode = explode(' ', $nombre[0]);
    foreach ($explode as $x) {
        $name .= $x[0];
    }
    return $name;
}

function getCorreoDirectiva($id)
{
    include('conexion.php');
    $query = "SELECT emailDirectivo FROM directivos WHERE idDirectivo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['emailDirectivo'];
}

// FIN FUNCION

function eliminar_acentos($cadena)
{

    //Reemplazamos la A y a
    $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena
    );

    //Reemplazamos la I y i
    $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena
    );

    //Reemplazamos la O y o
    $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena
    );

    //Reemplazamos la U y u
    $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena
    );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç'),
        array('N', 'n', 'C', 'c'),
        $cadena
    );

    return $cadena;
}

function getAnulacionSCPM($idSoliCompra)
{
    include('conexion.php');
    $query = 'SELECT * FROM anulaciones_solicompra_pm WHERE idSoliCompraPM = ' . $idSoliCompra;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idAnulacion'] = $row['idAnulacion'];
        $datos['detalleAnulacion'] = $row['detalleAnulacion'];
        $datos['personalAnulacion'] = $row['personalAnulacion'];
        $datos['unidadAnulacion'] = $row['unidadAnulacion'];
        $datos['fechaAnulacion'] = $row['fechaAnulacion'];
        $datos['idSoliCompraPM'] = $row['idSoliCompraPM'];
        return $datos;
    }
}

function getAnulacionSCSDO($idSoliCompra)
{
    include('conexion.php');
    $query = 'SELECT * FROM anulaciones_solicompra_sdo WHERE idSoliCompraSDO = ' . $idSoliCompra;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idAnulacion'] = $row['idAnulacion'];
        $datos['detalleAnulacion'] = $row['detalleAnulacion'];
        $datos['personalAnulacion'] = $row['personalAnulacion'];
        $datos['unidadAnulacion'] = $row['unidadAnulacion'];
        $datos['fechaAnulacion'] = $row['fechaAnulacion'];
        $datos['idSoliCompraSDO'] = $row['idSoliCompraSDO'];
        return $datos;
    }
}


function contarCotizacionesSoliCompraPM($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM cotizacion_solicompra_pm WHERE idSoliCompra = '" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function contarCotizacionesSoliCompraSDO($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM cotizacion_solicompra_sdo WHERE tipoSoliCompra = 'Normal' AND idSoliCompra = '" . $id . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function agregar_zip($dir, $zip)
{
    if (is_dir($dir)) {
        if ($da = opendir($dir)) {
            while (($archivo = readdir($da)) !== false) {
                if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
                    agregar_zip($dir . $archivo . "/", $zip);
                } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
                    $zip->addFile($dir . $archivo, $dir . $archivo);
                }
            }
            closedir($da);
        }
    }
}

function agregar_zipHT($dir, $zip)
{
    if (is_dir($dir)) {
        if ($da = opendir($dir)) {
            while (($archivo = readdir($da)) !== false) {
                if ($archivo != "." && $archivo != "..") {
                    if (is_dir($dir . $archivo)) {
                        agregar_zipHT($dir . $archivo . "/", $zip);
                    } elseif (is_file($dir . $archivo)) {
                        $zip->addFile($dir . $archivo, $archivo); // Corrige la ruta al añadir el archivo
                    }
                }
            }
            closedir($da);
        }
    }
}

// FIN FUNCION

function agregar_zipOC($dir, $zip)
{
    if (is_dir($dir)) {
        if ($da = opendir($dir)) {
            while (($archivo = readdir($da)) !== false) {
                if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
                    agregar_zipOC($dir . $archivo . "/", $zip);
                } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
                    $zip->addFile($dir . $archivo, $dir . $archivo);
                }
            }
            closedir($da);
        }
    }
}

function obtenerCantidadActas()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM actas";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerCantidadActasAntes($mes)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total from actas where cast(fechaActa as time) < '17:00:00' AND fechaActa BETWEEN '2022-0" . $mes . "-01 00:00:00' AND '2022-0" . $mes . "-31 23:59:59' AND tipoActa NOT LIKE 'Actualización' ORDER BY idActa ASC;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerCantidadActasDespues($mes)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total from actas where cast(fechaActa as time) > '17:00:00' AND fechaActa BETWEEN '2022-0" . $mes . "-01 00:00:00' AND '2022-0" . $mes . "-31 23:59:59' AND tipoActa NOT LIKE 'Actualización' ORDER BY idActa ASC;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getSoliReserva($id)

{
    include('conexion.php');
    $query = 'SELECT * FROM solicitudes_reserva WHERE idSoliReserva = ' . $id;

    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        //$datos['idSoliReserva '] = $row['idSoliReserva '];
        $datos['numReserva'] = $row['numReserva'];
        $datos['folioSoliCompra'] = $row['folioSoliCompra'];
        $datos['numeroCompra'] = $row['numeroCompra'];
        $datos['observaciones'] = $row['observaciones'];
        $datos['fechaSoliReserva'] = $row['fechaSoliReserva'];
        $datos['idPersonal'] = $row['idPersonal'];
        $datos['idDepartamento'] = $row['idDepartamento'];
        $datos['email'] = $row['email'];
        $datos['estadoSoliReserva'] = $row['estadoSoliReserva'];
        return $datos;
    }
}

// FIN FUNCION

function getSoliReservaHis($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM solicitudes_reserva_finalizadas WHERE idSoliReserva = ' . $id;

    // var_dump($query);
    // exit;

    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        //$datos['idSoliReserva '] = $row['idSoliReserva '];


        $datos['numReserva'] = $row['numReserva'];
        $datos['folioSoliCompra'] = $row['folioSoliCompra'];
        $datos['fechaSoliReserva'] = $row['fechaSoliReserva'];
        $datos['idPersonal'] = $row['idPersonal'];
        $datos['idDepartamento'] = $row['idDepartamento'];
        $datos['email'] = $row['email'];
        $datos['estadoSoliReserva'] = $row['estadoSoliReserva'];
        return $datos;
    }
}

// FIN FUNCION

//OBTENER DATA DE TAREAS

function getDataTarea($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM tareas_diarias WHERE idTarea = ' . $id;


    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idTarea'] = $row['idTarea'];
        $datos['tituloTarea'] = $row['tituloTarea'];
        $datos['detalleTarea'] = $row['detalleTarea'];
        $datos['nomSolicitante'] = $row['nomSolicitante'];
        $datos['nomEjecutor'] = $row['nomEjecutor'];
        $datos['estadoTarea'] = $row['estadoTarea'];
        $datos['inicioTarea'] = $row['inicioTarea'];
        $datos['terminoTarea'] = $row['terminoTarea'];
        $datos['limiteFecha'] = $row['limiteFecha'];
        $datos['prioridadTarea'] = $row['prioridadTarea'];
        return $datos;
    } else {
        return 'Error';
    }
}

function getCantTotalCCAA()
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as totalCCAA FROM control_acceso';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalCCAA'];
}

// FIN FUNCION

function getCantTotalActas()
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as totalActas FROM actas';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalActas'];
}

// FIN FUNCION

function getCantTotalParking()
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as totalParking FROM parking';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalParking'];
}

// FIN FUNCION

function saveEncPass($password)
{
    $epass = '20adeb83e85f03cfc84d0fb7e5f4d290';
    $epass = substr(hash('sha256', $epass, true), 0, 32);
    $metPass = 'aes-256-cbc';

    $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

    $encrpass = base64_encode(openssl_encrypt($password, $metPass, $epass, OPENSSL_RAW_DATA, $iv));
    return $encrpass;
}

function showDesPass($password)
{
    $dpass = '20adeb83e85f03cfc84d0fb7e5f4d290';
    $dpass = substr(hash('sha256', $dpass, true), 0, 32);
    $metPass = 'aes-256-cbc';

    $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

    $decrpass = openssl_decrypt(base64_decode($password), $metPass, $dpass, OPENSSL_RAW_DATA, $iv);
    return $decrpass;
}

function existePR($userPassReco, $aleatorioPassReco)
{
    if (!empty($userPassReco) && !empty($aleatorioPassReco)) {
        include('conexion.php');
        $query = "SELECT idPassReco FROM password_recovery WHERE userPassReco='" . $userPassReco . "' AND aleatorioPassReco='" . $aleatorioPassReco . "'";
        $res = mysqli_query($conexion, $query);
        $dato = mysqli_fetch_assoc($res);

        if ($dato['idPassReco'] > 0) {

            $query2 = "UPDATE password_recovery SET estadoPassReco = 'Proceso Realizado' WHERE idPassReco = " . $dato['idPassReco'];
            $res2 = mysqli_query($conexion, $query2);

            if ($res2) {
                return $dato['idPassReco'];
                // return $query2;
            } else {
                return "No Existe";
            }
        } else {
            return "No Existe";
        }
    } else {
        return "No Existe";
    }
}

// FIN FUNCION

function existePRticket($rutPassTicket, $aleatorioPassTicket)
{
    if (!empty($rutPassTicket) && !empty($aleatorioPassTicket)) {
        include('conexion.php');
        $query = "SELECT idPassTicket FROM password_recovery_tickets WHERE rutPassTicket='" . $rutPassTicket . "' AND aleatorioPassTicket='" . $aleatorioPassTicket . "'";
        $res = mysqli_query($conexion, $query);
        $dato = mysqli_fetch_assoc($res);

        if ($dato['idPassTicket'] > 0) {

            $query2 = "UPDATE password_recovery_tickets SET estadoPassTicket = 'Proceso Realizado' WHERE idPassTicket = " . $dato['idPassTicket'];
            $res2 = mysqli_query($conexion, $query2);

            if ($res2) {
                return $dato['idPassTicket'];
                // return $query2;
            } else {
                return "No Existe";
            }
        } else {
            return "No Existe";
        }
    } else {
        return "No Existe";
    }
}


function buscarTipoUserPassReco($idPassReco)
{
    include('conexion.php');
    $query = 'SELECT tipoUserPassReco FROM password_recovery WHERE idPassReco =' . $idPassReco;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['tipoUserPassReco'];
}


function buscarMotivo($nomMotivo)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) as total, nomMotivo FROM motivos_movilizacion WHERE nomMotivo ="' . $nomMotivo . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);

    // if($dato['total'] > 0){
    //   // return $dato['nomMotivo'];
    //   return 'Existe';
    // }else{
    //   // return $dato['total'];

    //   return 'No Existe';
    // }

    return $dato['total'];

    // return $query;
}

// FIN FUNCION

function ultimaOCCompraAC()
{
    include('conexion.php');
    $query = "SELECT ordenCompraAC FROM combustible_administracion ORDER BY fechaAC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['ordenCompraAC'];
}

// FIN FUNCION

function ultimaFacturaAC()
{
    include('conexion.php');
    $query = "SELECT facturaAC FROM combustible_administracion ORDER BY fechaAC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['facturaAC'];
}

// FIN FUNCION

function ultimaBoletaAC()
{
    include('conexion.php');
    $query = "SELECT boletaAC FROM combustible_administracion ORDER BY fechaAC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['boletaAC'];
}

// FIN FUNCION


function ultimaGuiaDespAC()
{
    include('conexion.php');
    $query = "SELECT guiaDespachoAC FROM combustible_administracion ORDER BY fechaAC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['guiaDespachoAC'];
}

// FIN FUNCION

function ultimaNoCargaAC()
{
    include('conexion.php');
    $query = "SELECT noCargaAC FROM combustible_administracion ORDER BY fechaAC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['noCargaAC'];
}

// FIN FUNCION
function ultimaPrecioAC()
{
    include('conexion.php');
    $query = "SELECT precioAC FROM combustible_administracion ORDER BY fechaAC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['precioAC'];
}

function ultimaCargaCombustible()
{
    include('conexion.php');
    $query = "SELECT fechaAC FROM combustible_administracion WHERE tipoAC = 'Carga de Combustible' ORDER BY fechaAC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['fechaAC'];
}

// FIN FUNCION

function cargaDiaTanque1()
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m-d', time());

    $inicio = $now . ' 08:00:00';
    $termino = $now . ' 19:00:00';


    $query = "SELECT terreno1MC FROM combustible_medicion WHERE fechaMC BETWEEN '" . $inicio . "' AND '" . $termino . "' ORDER BY fechaMC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['terreno1MC'];
}

// FIN FUNCION

function cargaDiaTanque2()
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    $now = date('m-Y', time());

    $inicio = $now . ' 08:00:00';
    $termino = $now . ' 19:00:00';


    $query = "SELECT terreno2MC FROM combustible_medicion WHERE fechaMC BETWEEN '" . $inicio . "' AND '" . $termino . "' ORDER BY fechaMC DESC limit 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['terreno2MC'];
}

// FIN FUNCION

function cargasMes()
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m', time());


    $inicio = $now . '-01';
    $termino = $now . '-31';

    $query = "SELECT COUNT(*) AS 'total' FROM combustible_administracion WHERE tipoAC = 'Carga de Combustible' AND fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function cargasMesSegunFecha($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');


    $inicio = $fecha . '-01';
    $termino = $fecha . '-31';

    $query = "SELECT COUNT(*) AS 'total' FROM combustible_administracion WHERE tipoAC = 'Carga de Combustible' AND fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function cargasMesAnterior()
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m', time());

    $antes = date("Y-m", strtotime($now . "- 1 month"));


    $inicio = $antes . '-01';
    $termino = $antes . '-31';

    $query = "SELECT COUNT(*) AS 'total' FROM combustible_administracion WHERE tipoAC = 'Carga de Combustible' AND fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function valorTotalViaje()
{
    include('conexion.php');

    $query = "SELECT SUM(valorViaje) AS total FROM viajes_movilizacion";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function valorTotalViajeNuevo()
{
    include('conexion.php');

    $query = "SELECT SUM(valorViaje) AS total FROM viajes_movilizacion_nuevo";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function valorTotalViajeNuevoVamoos()
{
    include('conexion.php');

    $query = "SELECT SUM(valorViaje) AS total FROM viajes_movilizacion_vamoos_spa";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function valorTotalExtension()
{
    include('conexion.php');

    $query = "SELECT SUM(valorExtension) AS total FROM viajes_movilizacion_nuevo";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function valorTotalExtensionVamoos()
{
    include('conexion.php');

    $query = "SELECT SUM(valorExtension) AS total FROM viajes_movilizacion_vamoos_spa";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function valorTotalExtensionViejo()
{
    include('conexion.php');

    $query = "SELECT SUM(valorExtension) AS total FROM viajes_movilizacion";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function primerMesRegistroCombustible()
{
    include('conexion.php');

    $query = "SELECT DISTINCT(DATE_FORMAT(fechaAC,'%Y-%m')) as meses FROM combustible_administracion ORDER BY fechaAC ASC LIMIT 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['meses'];
}

function ultimoMesRegistroCombustible()
{
    include('conexion.php');

    $query = "SELECT DISTINCT(DATE_FORMAT(fechaAC,'%Y-%m')) as meses FROM combustible_administracion ORDER BY fechaAC DESC LIMIT 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['meses'];
}

function obtenerMes($i)
{
    switch ($i) {
        case 1:
            return "Enero";
            break;
        case 2:
            return "Febrero";
            break;
        case 3:
            return "Marzo";
            break;
        case 4:
            return "Abril";
            break;
        case 5:
            return "Mayo";
            break;
        case 6:
            return "Junio";
            break;
        case 7:
            return "Julio";
            break;
        case 8:
            return "Agosto";
            break;
        case 9:
            return "Septiembre";
            break;
        case 10:
            return "Octubre";
            break;
        case 11:
            return "Noviembre";
            break;
        case 12:
            return "Diciembre";
            break;
        default:
            return "Número de mes inválido";
    }
}


function obtenerFechaConsumoAnterior($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');

    $sqlAnt = 'SELECT fechaAC FROM combustible_administracion WHERE fechaAC BETWEEN "' . $fecha . ' 00:00:00" AND "' . $fecha . ' 23:59:59" ORDER BY fechaAC DESC LIMIT 1';
    $resAnt = mysqli_query($conexion, $sqlAnt);
    $datoAnt = mysqli_fetch_assoc($resAnt);
    return $datoAnt['fechaAC'];
}

function obtenerConsumoMesEnergia($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM electricidad_administracion WHERE fechaLectura BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HBQP' ORDER BY fechaLectura ASC";
    /*    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['consumoEnergia'];
            $valorFinalAC = $row['consumoEnergia'];
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {
            if ($row['tipoHospital'] == 'HBQP') {
                $diferencia = $row['consumoEnergia'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['consumoEnergia'];
            } else {
                $diferencia = $numAnterior - $row['consumoEnergia'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['consumoEnergia'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}

function obtenerConsumoMesEnergia1($conexion, $fecha)
{
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';
    $termino = $fecha . '-31';

    $consumoT1 = 0;

    $query = "SELECT * FROM electricidad_administracion WHERE fechaLectura BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HBQP' ORDER BY fechaLectura ASC";

    $res = mysqli_query($conexion, $query);

    if (!$res) {
        // Manejo de errores, por ejemplo:
        return false;
    }

    $numAC = 1;
    $numAnterior = 0;

    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['consumoEnergia'];
            $consumoT1 = $numAnterior;
        } else {
            $diferencia = $row['consumoEnergia'] - $numAnterior;
            $numAnterior = $row['consumoEnergia'];
            $consumoT1 += $diferencia;
        }
        $numAC++;
    }

    return $consumoT1;
}


function obtenerConsumoGasesClinicos($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM gases_clinicos WHERE fechaIngreso BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaIngreso ASC";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['valorTotalNeto'];
            $valorFinalAC = $row['valorTotalNeto'];
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {
            if ($row['valorTotalNeto'] == 0 || empty($row['valorTotalNeto'])) {
                $diferencia = $row['valorTotalNeto'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['valorTotalNeto'];
            } else {
                $diferencia = $numAnterior - $row['valorTotalNeto'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['valorTotalNeto'];
            }
        }
        $numAC++;
    }
    return $consumoT1;
}

function obtenerMontoIvaGasesClinicos($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM gases_clinicos WHERE fechaIngreso BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaIngreso ASC";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['valorTotalIva'];
            $valorFinalAC = $row['valorTotalIva'];
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {
            if ($row['valorTotalIva'] == 0 || empty($row['valorTotalIva'])) {
                $diferencia = $row['valorTotalIva'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['valorTotalIva'];
            } else {
                $diferencia = $numAnterior - $row['valorTotalIva'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['valorTotalIva'];
            }
        }
        $numAC++;
    }
    return $consumoT1;
}

function obtenerConsumoMesAgua($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HBQP' ORDER BY fechaDocumento ASC";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['consumoAgua'];
            $valorFinalAC = $row['consumoAgua'];
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {
            if ($row['tipoHospital'] == 'HBQP') {
                $diferencia = $row['consumoAgua'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['consumoAgua'];
            } else {
                $diferencia = $numAnterior - $row['consumoAgua'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['consumoAgua'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}

function obtenerCargo($personaSoli)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM jefes_tecnicos WHERE nomJefe LIKE '" . $personaSoli . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function obtenerCargoNom($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT cargoJefe FROM jefes_tecnicos WHERE nomJefe LIKE '" . $nomUsuario . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cargoJefe'];
}

function obtenerCargoNomPersonal($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT cargoPersonal FROM personal WHERE nomPersonal LIKE '" . $nomUsuario . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cargoPersonal'];
}

function obtenerCargoNomAdmin($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT rolAdministrador FROM administracion WHERE nomAdministrador LIKE '" . $nomUsuario . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomAdministrador'];
}

function obtenerCargoAdmin($personaSoli)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM administracion WHERE nomAdministrador LIKE '" . $personaSoli . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato;
}

function obtenerCargoDirectiva($personaSoli)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as TOTAL FROM directivos WHERE nomDirectivo LIKE '" . $personaSoli . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato;
}

function obtenerNombreAdmin($personaSoli)
{
    include('conexion.php');
    $query1 = "SELECT * FROM administracion WHERE nomAdministrador LIKE '" . $personaSoli . "'";
    $res2 = mysqli_query($conexion, $query1);
    while ($row2 = $res2->fetch_assoc()) {
        $cargos = $row2['rolAdministrador'];
    }
    return $cargos;
}

function obtenerNombreDirectivo($personaSoli)
{
    include('conexion.php');
    $query = "SELECT * FROM directivos WHERE nomDirectivo LIKE '" . $personaSoli . "'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $cargos = $row['cargoDirectivo'];
    }
    return $cargos;
}

function obtenerNombreJefe($personaSoli)
{
    include('conexion.php');
    $query = "SELECT * FROM jefes_tecnicos WHERE nomJefe LIKE '" . $personaSoli . "'";
    $res1 = mysqli_query($conexion, $query);
    while ($row1 = $res1->fetch_assoc()) {
        $cargos = $row1['cargoJefe'];
    }
    return $cargos;
}

function obtenerMontodelMes($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');
    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM electricidad_administracion WHERE fechaLectura BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HBQP' ORDER BY fechaLectura ASC";
    /*    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['montoTotalMes'];
            $valorFinalAC = $row['montoTotalMes'];
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {
            if ($row['tipoHospital'] == 'HBQP') {
                $diferencia = $row['montoTotalMes'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['montoTotalMes'];
            } else {
                $diferencia = $numAnterior - $row['montoTotalMes'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['montoTotalMes'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}

function obtenerConsumoHSMQ($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';
    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;
    $numOP = 1;


    $query = "SELECT * FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['consumoEnergia'];
            $valorFinalAC = $row['consumoEnergia'];
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {
            if ($row['tipoHospital'] == 'HSMQ') {
                $diferencia = $row['consumoEnergia'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['consumoEnergia'];
            } else {
                $diferencia = $numAnterior - $row['consumoEnergia'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['consumoEnergia'];
            }
        }
        $numAC++;
    }
    return $consumoT1;
}

function obtenerSumaTotalHSMQ($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';


    $query = "SELECT SUM(consumoEnergia) as TOTALSUMA FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HSMQ'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMA = $row['TOTALSUMA'];
        if (empty($TOTALSUMA)) {
            $TOTALSUMA = 0;
        }
    }
    return $TOTALSUMA;
}

function obtenerSumaCosumodeTodo($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $query = "SELECT SUM(consumoEnergia) as TOTALSUMACONSUMOTODO FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMACONSUMOTODO = $row['TOTALSUMACONSUMOTODO'];
        if (empty($TOTALSUMACONSUMOTODO)) {
            $TOTALSUMACONSUMOTODO = 0;
        }
    }
    return $TOTALSUMACONSUMOTODO;
}

function totalsumaconsumototal($anio)
{
    include('conexion.php');
    $SQLCONSUMO = "SELECT SUM(consumoEnergia) as TotalSumaConsumoTotal FROM electricidad_administracion WHERE YEAR(fechaDocumento) = '$anio'";
    $RESCONSUMO = mysqli_query($conexion, $SQLCONSUMO);
    while ($ROWCONSUMO = $RESCONSUMO->fetch_assoc()) {
        $TotalSumaConsumoTotal = $ROWCONSUMO['TotalSumaConsumoTotal'];
        if (empty($TotalSumaConsumoTotal)) {
            $TotalSumaConsumoTotal = 0;
        }
    }
    return $TotalSumaConsumoTotal;
}

function totalsumamontototal($anio)
{
    include('conexion.php');
    $SQLMONTOTOTALENERGIA = "SELECT SUM(montoTotalMes) as TotalSumaMontoTotal FROM electricidad_administracion WHERE YEAR(fechaDocumento) = '$anio'";
    $RESMONTOENERGIA = mysqli_query($conexion, $SQLMONTOTOTALENERGIA);
    while ($ROWMONTO = $RESMONTOENERGIA->fetch_assoc()) {
        $TotalSumaMontoTotal = $ROWMONTO['TotalSumaMontoTotal'];
        if (empty($TotalSumaMontoTotal)) {
            $TotalSumaMontoTotal = 0;
        }
    }
    return $TotalSumaMontoTotal;
}

function consumoaguhbqp($anio)
{
    include('conexion.php');
    $sqlSuma = "SELECT SUM(consumoAgua) as total FROM agua_administracion WHERE tipohospital = 'HBQP' AND YEAR(fechaDocumento) = '$anio'";
    $resSuma = mysqli_query($conexion, $sqlSuma);
    while ($rowSuma = $resSuma->fetch_assoc()) {
        $total = $rowSuma['total'];
    }
    return $total;
}

function montototalaguahbqp($anio)
{
    include('conexion.php');
    $sqlMonto = "SELECT SUM(montoTotalMes) as totalMonto FROM agua_administracion WHERE tipoHospital = 'HBQP' AND YEAR(fechaDocumento) = '$anio'";
    $resMonto = mysqli_query($conexion, $sqlMonto);
    while ($rowMonto = $resMonto->fetch_assoc()) {
        $totalMonto = $rowMonto['totalMonto'];
    }
    return $totalMonto;
}

function obtenerSumaConsumodeTodoAgua($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $query = "SELECT SUM(consumoAgua) as TOTALSUMACONSUMOTODOAGUA FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMACONSUMOTODOAGUA = $row['TOTALSUMACONSUMOTODOAGUA'];
        if (empty($TOTALSUMACONSUMOTODOAGUA)) {
            $TOTALSUMACONSUMOTODOAGUA = 0;
        }
    }
    return $TOTALSUMACONSUMOTODOAGUA;
}

function obtenerSumaTotaldeTodoAgua($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $query = "SELECT SUM(montoTotalMes) as TOTALSUMAMONTOTODOAGUA FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMAMONTOTODOAGUA = $row['TOTALSUMAMONTOTODOAGUA'];
        if (empty($TOTALSUMAMONTOTODOAGUA)) {
            $TOTALSUMAMONTOTODOAGUA = 0;
        }
    }
    return $TOTALSUMAMONTOTODOAGUA;
}

function obtenerSumaTotaldeTodo($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $query = "SELECT SUM(montoTotalMes) as TOTALSUMAMONTOTODO FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMAMONTOTODO = $row['TOTALSUMAMONTOTODO'];
        if (empty($TOTALSUMAMONTOTODO)) {
            $TOTALSUMAMONTOTODO = 0;
        }
    }
    return $TOTALSUMAMONTOTODO;
}

function obtenerSumaTotalAguaHSMQ($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $query = "SELECT SUM(consumoAgua) as TOTALSUMAAGUA FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HSMQ'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMAAGUA = $row['TOTALSUMAAGUA'];
        if (empty($TOTALSUMAAGUA)) {
            $TOTALSUMAAGUA = 0;
        }
    }
    return $TOTALSUMAAGUA;
}

function obtenerSumaTotalMontoHSMQ($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';


    $query = "SELECT SUM(montoTotalMes) as TOTALSUMAMONTO FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HSMQ'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMAMONTO = $row['TOTALSUMAMONTO'];
        if (empty($TOTALSUMAMONTO)) {
            $TOTALSUMAMONTO = 0;
        }
    }
    return $TOTALSUMAMONTO;
}

function obtenerSumaTotalMontoAguaHSMQ($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');

    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $query = "SELECT SUM(montoTotalMes) as TOTALSUMAMONTOAGUA FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HSMQ'";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $TOTALSUMAMONTOAGUA = $row['TOTALSUMAMONTOAGUA'];
        if (empty($TOTALSUMAMONTOAGUA)) {
            $TOTALSUMAMONTOAGUA = 0;
        }
    }
    return $TOTALSUMAMONTOAGUA;
}

function obtenerMontodelMesAgua($fecha)
{
    include('conexion.php');
    date_default_timezone_set('America/Santiago');
    $inicio = $fecha . '-01';

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoHospital = 'HBQP' ORDER BY fechaDocumento ASC";
    /*    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {
        if ($numAC == 1) {
            $numAnterior = $row['montoTotalMes'];
            $valorFinalAC = $row['montoTotalMes'];
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {
            if ($row['tipoHospital'] == 'HBQP') {
                $diferencia = $row['montoTotalMes'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['montoTotalMes'];
            } else {
                $diferencia = $numAnterior - $row['montoTotalMes'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['montoTotalMes'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}


function obtenerConsumoMesT1($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    // $now = date('Y-m', time());


    $inicio = $fecha . '-01';

    /*$timeInicio = strtotime($inicio."- 1 days");

    $inicio2 = date("Y-m-d",$timeInicio);

    $inicio3 = obtenerFechaConsumoAnterior($inicio2);*/

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";
    /*    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {
        // PARA TANQUE 1
        if ($numAC == 1) {
            $numAnterior = $row['tanque1AC'];
            $valorFinalAC = '0';
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {

            if ($row['tipoAC'] == 'Carga de Combustible') {

                $diferencia = $row['tanque1AC'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                    // $consumoT1 = '+'.$diferencia;
                    // echo 'c    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '+';
                }
                $numAnterior = $row['tanque1AC'];
            } else {
                $diferencia = $numAnterior - $row['tanque1AC'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    // $identificadorAC1 = '0';
                    // echo 'd    '.$consumoT1.'<br><br>';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['tanque1AC'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}

function obtenerConsumoMesT2($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    // $now = date('Y-m', time());


    $inicio = $fecha . '-01';

    /*$timeInicio = strtotime($inicio."- 1 days");

    $inicio2 = date("Y-m-d",$timeInicio);

    $inicio3 = obtenerFechaConsumoAnterior($inicio2);*/

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";
    /*    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {
        // PARA TANQUE 1
        if ($numAC == 1) {
            $numAnterior = $row['tanque2AC'];
            $valorFinalAC = '0';
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {

            if ($row['tipoAC'] == 'Carga de Combustible') {

                $diferencia = $row['tanque2AC'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                    // echo 'b    '.$consumoT1.'<br><br>';
                    // $identificadorAC1 = '0';
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                }
                $numAnterior = $row['tanque2AC'];
            } else {
                $diferencia = $numAnterior - $row['tanque2AC'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['tanque2AC'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}

function obtenerConsumoMesT3($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    // $now = date('Y-m', time());


    $inicio = $fecha . '-01';

    /*$timeInicio = strtotime($inicio."- 1 days");

    $inicio2 = date("Y-m-d",$timeInicio);

    $inicio3 = obtenerFechaConsumoAnterior($inicio2);*/

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";
    /*    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {
        // PARA TANQUE 1
        if ($numAC == 1) {
            $numAnterior = $row['tanque3AC'];
            $valorFinalAC = '0';
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {

            if ($row['tipoAC'] == 'Carga de Combustible') {

                $diferencia = $row['tanque3AC'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                }
                $numAnterior = $row['tanque3AC'];
            } else {
                $diferencia = $numAnterior - $row['tanque3AC'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['tanque3AC'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}

function obtenerConsumoMesT4($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    // $now = date('Y-m', time());


    $inicio = $fecha . '-01';

    /*$timeInicio = strtotime($inicio."- 1 days");

    $inicio2 = date("Y-m-d",$timeInicio);

    $inicio3 = obtenerFechaConsumoAnterior($inicio2);*/

    $termino = $fecha . '-31';

    $numAC = 1;
    $numAnterior = 0;
    $valorFinalAC = 0;
    $consumoT1 = 0;

    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";
    /*    $query = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);

    while ($row = $res->fetch_assoc()) {
        // PARA TANQUE 1
        if ($numAC == 1) {
            $numAnterior = $row['tanque4AC'];
            $valorFinalAC = '0';
            $consumoT1 = $valorFinalAC;
            // echo 'a    '.$consumoT1.'<br><br>';
        } else {

            if ($row['tipoAC'] == 'Carga de Combustible') {

                $diferencia = $row['tanque4AC'] - $numAnterior;
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                    $consumoT1 = $consumoT1 + $diferencia;
                } else {
                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                }
                $numAnterior = $row['tanque4AC'];
            } else {
                $diferencia = $numAnterior - $row['tanque4AC'];
                if ($diferencia == 0) {
                    $valorFinalAC = '';
                } else {
                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                    if (strpos($valorFinalAC, '- -') !== false) {
                        $diferencia = 0;
                    }
                    $consumoT1 = $consumoT1 - $diferencia;
                    // echo 'f    '.$consumoT1.'<br><br>';
                }
                $numAnterior = $row['tanque4AC'];
            }
        }
        $numAC++;
    }

    return $consumoT1;
}


function obtenerPromedioACMes($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    // $now = date('Y-m', time());


    $inicio = $fecha . '-01';

    /*$timeInicio = strtotime($inicio."- 1 days");

    $inicio2 = date("Y-m-d",$timeInicio);

    $inicio3 = obtenerFechaConsumoAnterior($inicio2);*/
    $termino = $fecha . '-31';

    $query = "SELECT AVG(precioAC) AS PromPrecioAC FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";
    /*    $query = "SELECT AVG(precioAC) AS PromPrecioAC FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['PromPrecioAC'];
}

function obtenerPromedioACMes2($fecha)
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    // $now = date('Y-m', time());


    $inicio = $fecha . '-01';

    /*$timeInicio = strtotime($inicio."- 1 days");

    $inicio2 = date("Y-m-d",$timeInicio);

    $inicio3 = obtenerFechaConsumoAnterior($inicio2);*/
    $termino = $fecha . '-31';

    $query = "SELECT AVG(precioAC) AS PromPrecioAC FROM combustible_administracion WHERE precioAC > 0 AND fechaAC BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";
    /*    $query = "SELECT AVG(precioAC) AS PromPrecioAC FROM combustible_administracion WHERE fechaAC BETWEEN '" . $inicio3 . "' AND '" . $termino . " 23:59:59' ORDER BY fechaAC ASC";*/
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['PromPrecioAC'];
}

function getNomDepartamentoCCAA($id)
{
    include('conexion.php');
    $query = 'SELECT DEPT_FULL_NAME FROM Department_table WHERE DEPT_ID=' . $id . '';
    $res = mysqli_query($conexion, $query);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);

        if ($row) {
            // Verificamos si "CARD_NUMBER" es vacío o nulo
            if (empty($row['DEPT_FULL_NAME'])) {
                return "Sin Información1";
            } else {
                return $row['DEPT_FULL_NAME'];
            }
        } else {
            return "Sin Información2";
        }
    } else {
        return "Sin Información3";
    }
}

// FIN FUNCION

function existeIntercom($rut)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM intercom WHERE Name="' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerMesCursoActas()
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m', time());

    $inicio = $now . '-01';
    $termino = $now . '-31';

    $query = "SELECT COUNT(*) AS total FROM estadistica_actas WHERE mesEstadActa BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerCantidadActasAntesFinal()
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m', time());

    $inicio = $now . '-01';
    $termino = $now . '-31';

    $query = "SELECT COUNT(*) AS total from actas where cast(fechaActa as time) < '17:00:00' AND fechaActa BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoActa NOT LIKE 'Actualización' ORDER BY idActa ASC;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerCantidadActasDespuesFinal()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total from actas where cast(fechaActa as time) > '17:00:00' AND fechaActa BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND tipoActa NOT LIKE 'Actualización' ORDER BY idActa ASC;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerIDMesCursoActas()
{
    include('conexion.php');

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m', time());

    $inicio = $now . '-01';
    $termino = $now . '-31';

    $query = "SELECT idEstadActa FROM estadistica_actas WHERE mesEstadActa BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' LIMIT 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idEstadActa'];
}

//CONTAR HOJAS TECNICAS POR DEPARTAMENTO EN UN AÑO
function contarHojasTecnicasAnio($id, $anio)
{

    $fechaInicio = $anio . '-01-01 00:00:00';
    $fechaTermino = $anio . '-12-31 23:59:59';

    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM  ordenes_trabajos_general WHERE idDepartamento = " . $id . " AND fechaOrden BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "' ";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarTicketSIGHanio($nomJefe, $anio)
{
    $fechaInicio = $anio . '-01-01 00:00:00';
    $fechaTermino = $anio . '-12-31 23:59:59';
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM ticket_sigh WHERE rutTecnicoTicketSIGH = '$nomJefe' AND fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "' ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function contarOTAnio($idDepartamentOT, $anioT)
{
    $fechaInicio = $anioT . '-01-01 00:00:00';
    $fechaTermino = $anioT . '-12-31 23:59:59';
    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM trabajos_general WHERE departamento = " . $idDepartamentOT . " AND fechaTermino BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "' ";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $query;
}


function contarTicketsAnio($nom, $anio)
{
    $fechaInicio = $anio . '-01-01 00:00:00';
    $fechaTermino = $anio . '-12-31 23:59:59';

    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE deptoResponsable = " . $nom . " AND inicioSolicitud BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "'";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

//CONTAR HOJAS TECNICAS POR DEPARTAMENTO EN UN AÑO
function contarHojasTecnicasMesAnio($id, $fecha)
{

    $fechaInicio = $fecha . '-01 00:00:00';
    $fechaTermino = $fecha . '-31 23:59:59';

    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM  ordenes_trabajos_general WHERE idDepartamento = " . $id . " AND fechaOrden BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "' ";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarTicketSIGHMesAnio($nomEmpleado, $fechaT)
{
    $fechaInicio = $fechaT . '-01 00:00:00';
    $fechaTermino = $fechaT . '-31 23:59:59';

    include('conexion.php');


    $query = "SELECT COUNT(*) as total FROM  ticket_sigh WHERE rutTecnicoTicketSIGH LIKE '$nomEmpleado' AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total']; // Formateamos el total con comas
}

function contarCancelados($anio, $j)
{
    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM ticket_sigh WHERE estadoTicketSIGH LIKE 'Cancelado' and (YEAR(fechaSolicTicketSIGH) = '$anio' and MONTH(fechaSolicTicketSIGH) = '$j') ";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total']; // Formateamos el total con com
}


function contarfinalizadaspordepa($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE rutTecnicoTicketSIGH = '$rut_con_guionOT' AND (estadoTicketSIGH LIKE 'Finalizado') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia']; // Formateamos el total con comas

}

function contarfinalizadaspordepaAnio($rut_con_guionOT, $anio)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE rutTecnicoTicketSIGH = '$rut_con_guionOT' AND estadoTicketSIGH = 'Finalizado' AND YEAR(fechaSolicTicketSIGH) = '$anio'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia']; // Formateamos el total con comas
}

function contardesarrollodepaAnio($rut_con_guionOTAnio, $anio)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE rutTecnicoTicketSIGH LIKE '$rut_con_guionOTAnio' AND estadoTicketSIGH LIKE 'En Desarrollo' AND YEAR(fechaSolicTicketSIGH) = '$anio'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia']; // Formateamos el total con comas
}

function contarpendientesdepaAnio($rut_con_guionOTAnio, $anio)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE rutTecnicoTicketSIGH LIKE '$rut_con_guionOTAnio' AND estadoTicketSIGH LIKE 'Pendiente' AND YEAR(fechaSolicTicketSIGH) = '$anio'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia']; // Formateamos el total con comas
}

function contaranios($rut_con_guionOTAnio, $anio)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE rutTecnicoTicketSIGH LIKE '$rut_con_guionOTAnio' AND YEAR(fechaSolicTicketSIGH) = '$anio'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia']; // Formateamos el total con comas
}

function contarcanceladasdepa($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE rutTecnicoTicketSIGH LIKE '$rut_con_guionOT' AND estadoTicketSIGH LIKE 'Cancelado' AND fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function contardesarrollodepa($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'En Desarrollo') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function contarpendientesdepa($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Pendiente') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function contarprogramadodepa($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Programado') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function contarcanceladosdepa($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Cancelado') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function buscarTicketsPendientes($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT rutTecnicoTicketSIGH, numTicketSIGH FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Pendiente') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);

    $datos = array();
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $datos[] = $row;
        }
    } else {
        $datos = 'vacio';
    }

    mysqli_close($conexion);
    return $datos;
}

function buscarTicketsEnDesarrollo($rut_con_guionOT, $meses)
{
//    $meses = '2024-07';
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT rutTecnicoTicketSIGH, numTicketSIGH FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'En Desarrollo') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
//    $query = "SELECT COUNT(*) AS total FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'En Desarrollo') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);

    $datos = array();
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $datos[] = $row;
        }
    } else {
        $datos = 'vacio';
    }

    mysqli_close($conexion);
    return $datos;
}

function buscarTicketsProgramado($rut_con_guionOT, $meses)
{
    include('conexion.php');
    $fechaInicio = $meses . '-01 00:00:00';
    $fechaTermino = $meses . '-31 23:59:59';
    $query = "SELECT rutTecnicoTicketSIGH, numTicketSIGH FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Programado') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);

    $datos = array();
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $datos[] = $row;
        }
    } else {
        $datos = 'vacio';
    }

    mysqli_close($conexion);
    return $datos;
}

function visualizarInformacion($array)
{
    if (empty($array)) {
        echo "<li>Sin Tickets</li>";
    } else {
        foreach ($array as $ticket) {
            echo "<li>Número de Ticket: " . $ticket['numTicketSIGH'] . '</li>';
        }
    }
}

function contarOTMesAnio($id, $fecha)
{
    $fechaInicio = $fecha . '-01 00:00:00';
    $fechaTermino = $fecha . '-31 23:59:59';

    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM trabajos_general WHERE departamento = " . $id . " AND fechaTermino BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "' ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function contarTicketsMesAnio($nom, $fecha)
{
    $fechaInicio = $fecha . '-01 00:00:00';
    $fechaTermino = $fecha . '-31 23:59:59';

    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM solicitudes_soportes WHERE deptoResponsable = '" . $nom . "' AND inicioSolicitud BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "' ";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerCantidadDepartamentos()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM departamentos WHERE nomDepartamento NOT LIKE 'Ingeniería Operacional'  AND nomDepartamento NOT LIKE 'Informática'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function obtenerPrimerAnio()
{
    include('conexion.php');
    $query = "SELECT DISTINCT(DATE_FORMAT(fechaAC,'%Y')) as anio FROM combustible_administracion ORDER BY fechaAC ASC LIMIT 1;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['anio'];
}


function getBoletinIncendio($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM boletin_incendio WHERE idBoletinIncendio = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idBoletinIncendio'] = $row['idBoletinIncendio'];
        $datos['folioBoletinIncendio'] = $row['folioBoletinIncendio'];
        $datos['fechaBoletinIncendio'] = $row['fechaBoletinIncendio'];
        $datos['servicioBoletinIncendio'] = $row['servicioBoletinIncendio'];
        $datos['pisoBoletinIncendio'] = $row['pisoBoletinIncendio'];
        $datos['pmaBoletinIncendio'] = $row['pmaBoletinIncendio'];
        $datos['categoriaBoletinIncendio'] = $row['categoriaBoletinIncendio'];
        $datos['motivoBoletinIncendio'] = $row['motivoBoletinIncendio'];
        $datos['descripBoletinIncendio'] = $row['descripBoletinIncendio'];
        $datos['creadorBoletinIncendio'] = $row['creadorBoletinIncendio'];
        return $datos;
    }
}

// FIN FUNCION

function getUltimoID()
{
    include('conexion.php');
    $query = 'SELECT idSoliCompra FROM solicitudes_compra_sdo ORDER BY idSoliCompra DESC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idSoliCompra'];
}

function obtenerTotalArchivos($idOrden)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM archivos_orden_general WHERE idOrden = " . $idOrden;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION


function getTG($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM trabajos_general WHERE idTG = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idTG'] = $row['idTG'];
        $datos['folioTG'] = $row['folioTG'];
        $datos['solicitanteTG'] = $row['solicitanteTG'];
        $datos['detalleTG'] = $row['detalleTG'];
        $datos['fechaTG'] = $row['fechaTG'];
        $datos['estadoTG'] = $row['estadoTG'];
        $datos['turnoTG'] = $row['turnoTG'];
        $datos['tecnico1'] = $row['tecnico1'];
        $datos['tecnico2'] = $row['tecnico2'];
        $datos['tecnicoReemplazo1'] = $row['tecnicoReemplazo1'];
        $datos['tecnicoReemplazo2'] = $row['tecnicoReemplazo2'];
        $datos['observaciones'] = $row['observaciones'];
        $datos['tipoTecnico'] = $row['tipoTecnico'];
        $datos['fechaSubidaTecnico'] = $row['fechaSubidaTecnico'];
        $datos['avanceTGT'] = $row['avanceTGT'];
        $datos['observacionTGT'] = $row['observacionTGT'];
        return $datos;
    }
}

// FIN FUNCION

function getUsuariosTG($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM usuarios_trabajo_general WHERE idTG = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idUTG'] = $row['idUTG'];
        $datos['nomUTG'] = $row['nomUTG'];
        $datos['cargoUTG'] = $row['cargoUTG'];
        $datos['idTG'] = $row['idTG'];
        return $datos;
    }
}

// FIN FUNCION

function buscarRutTicket($rut)
{
    include('conexion.php');
    $query = 'SELECT COUNT(*) AS total FROM usuarios_tickets WHERE rutUsuariosT LIKE "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getDatosUT($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM usuarios_tickets WHERE idUsuariosT = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idUT'] = $row['idUsuariosT'];
        $datos['nomUT'] = $row['nomUsuariosT'];
        $datos['rutUT'] = $row['rutUsuariosT'];
        $datos['passUT'] = $row['passUsuariosT'];
        $datos['emailUT'] = $row['emailUsuariosT'];
        $datos['cargoUT'] = $row['cargoUsuariosT'];
        $datos['unidadUT'] = $row['unidadUsuariosT'];
        $datos['telefonoUT'] = $row['telefonoUsuariosT'];
        $datos['activoUT'] = $row['activoUsuariosT'];
        return $datos;
    }
}

function verificarRUTTicket($rut)
{
    include('conexion.php');
    $query = 'SELECT activoUsuariosT FROM usuarios_tickets WHERE rutUsuariosT LIKE "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['activoUsuariosT'];
}

function buscarIDDeptoCCAA($rut)
{
    include('conexion.php');
    $query = 'SELECT DEPT_ID FROM ACM_owner WHERE OWNER_CODE LIKE "' . $rut . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['DEPT_ID'];
}

function buscarNomDeptoCCAA($id)
{
    include('conexion.php');
    $query = 'SELECT DEPT_FULL_NAME FROM Department_table WHERE DEPT_ID = "' . $id . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['DEPT_FULL_NAME'];
}

function getIDCarpetaPadre($id)
{
    include('conexion.php');
    $query = 'SELECT idCarpetaPadre FROM carpeta_control WHERE idCarpeta = "' . $id . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCarpetaPadre'];
}

function getNomCarpetaPadre($id)
{
    include('conexion.php');
    $query = 'SELECT nomCarpeta FROM carpeta_control WHERE idCarpeta = "' . $id . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomCarpeta'];
}

function getRutaCarpetaPadre($id)
{
    include('conexion.php');
    $query = 'SELECT rutaCarpeta FROM carpeta_control WHERE idCarpeta = "' . $id . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaCarpeta'];
}

function getCantidadArchivosCarpetas($ruta)
{
    $listado = glob($ruta);
    $cantidadArchivo = 0;
    foreach ($listado as $elemento) {
        $cantidadArchivo++;
        //    unlink($elemento);

    }
    return $cantidadArchivo;
}

function getRutaArchivosCarpetas($id)
{
    include('conexion.php');
    $query = 'SELECT rutaCarpeta FROM carpeta_control WHERE idCarpeta = "' . $id . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaCarpeta'];
}

function getRutaArchivoC($id)
{
    include('conexion.php');
    $query = 'SELECT rutaArchivoC FROM carpeta_archivos WHERE idArchivoC = "' . $id . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaArchivoC'];
}

function getNomArchivoC($id)
{
    include('conexion.php');
    $query = 'SELECT nomArchivoC FROM carpeta_archivos WHERE idArchivoC = "' . $id . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomArchivoC'];
}

function getCantidadUnidadMovilizacion()
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS total FROM motivos_movilizacion WHERE motivoUnidad = 1";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getCantidadInvUsado($idOrden)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS total FROM inventario_usados WHERE idOrden = " . $idOrden;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerInventarioEquipos($carpeta)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM inventario_equipos WHERE categoriaInventario = $carpeta ";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $total = $row['total'];
        } else {
            $total = 0;
        }
    } else {
        $total = 0;
    }

    return $total;
}

function obtenerCantMaxInvent($idInventario)
{
    include 'conexion.php';
    $sql = "SELECT cantidadInventario FROM inventario_equipos WHERE idInventario = $idInventario ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidadInventario'];
}


function getEquipoInventario($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM inventario_equipos WHERE idInventario = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idInventario'] = $row['idInventario'];
        $datos['equipoInventario'] = $row['equipoInventario'];
        $datos['marcaInventario'] = $row['marcaInventario'];
        $datos['modeloInventario'] = $row['modeloInventario'];
        $datos['cantidadInventario'] = $row['cantidadInventario'];
        $datos['categoriaInventario'] = $row['categoriaInventario'];
        $datos['fechaInventario'] = $row['fechaInventario'];
        return $datos;
    }
}

// FIN FUNCION

function getACMOwner($OWNER_CODE)
{
    include('conexion.php');
    $query = 'SELECT * FROM ACM_owner WHERE OWNER_CODE = "' . $OWNER_CODE . '"';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['OWNER_CODE'] = $row['OWNER_CODE'];
        $datos['OWNER_Name'] = $row['OWNER_Name'];
        $datos['DEPT_ID'] = $row['DEPT_ID'];
        $datos['nomDepartament'] = buscarNomDeptoCCAA($row['DEPT_ID']);
        $datos['CARD_NUMBER'] = $row['CARD_NUMBER'];
        $datos['CARD_STATE'] = $row['CARD_STATE'];
        $datos['END_DATE'] = $row['END_DATE'];
        return $datos;
    }
}

function getACMOwnerConFecha($OWNER_CODE, $operatorTime)
{
    include('conexion.php');
    $query = 'SELECT * FROM ACM_owner WHERE OWNER_CODE = "' . $OWNER_CODE . '"';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['OWNER_CODE'] = $row['OWNER_CODE'];
        $datos['OWNER_Name'] = $row['OWNER_Name'];
        $datos['DEPT_ID'] = $row['DEPT_ID'];
        $datos['nomDepartament'] = buscarNomDeptoCCAA($row['DEPT_ID']);
        $datos['CARD_NUMBER'] = $row['CARD_NUMBER'];
        $datos['CARD_STATE'] = $row['CARD_STATE'];
        $datos['END_DATE'] = $row['END_DATE'];
        $datos['OperatorTime'] = $operatorTime;
        return $datos;
    }
}

function obtenerNombrexACMOwner($OWNER_CODE)
{
    include 'conexion.php';
    $sql = "SELECT OWNER_Name FROM ACM_owner WHERE OWNER_CODE = '" . $OWNER_CODE . "' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['OWNER_Name'];
}

function obtenerDeptxACMOwner($OWNER_CODE)
{
    include 'conexion.php';
    $sql = "SELECT DEPT_ID FROM ACM_owner WHERE OWNER_CODE = '" . $OWNER_CODE . "' ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['DEPT_ID'];
}

function obtenerNameGrupoPuerta($idGrupoPuerta)
{
    include 'conexion.php';
    $sql = "SELECT doorgroupname FROM ACS_DOOR_GROUP WHERE doorgroupid = $idGrupoPuerta ";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['doorgroupname'];
}

function obtenerRutaHTFirmada($idOrden)
{
    include 'conexion.php';
    $sql = "SELECT rutaArchivoHT FROM archivos_hoja_firmada WHERE idHoja = " . $idOrden . " ORDER BY idArchivoHT DESC";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaArchivoHT'];
}


function obtenerUltimoRegistroMantenimiento($idVehiculo)
{
    include 'conexion.php';
    // Ordenamos primero por fecha y luego por ID descendente (el más nuevo arriba)
    $sql = "SELECT cantKilometraje FROM historial_kilometraje_moviles 
            WHERE idMovil = " . intval($idVehiculo) . " AND registroMantenimiento = 1 
            ORDER BY fechaRegistro DESC, idKilometraje DESC LIMIT 1";

    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);

    // Si no hay datos, retornamos 0 para evitar errores en el cálculo
    return ($dato) ? $dato['cantKilometraje'] : 0;
}

function obtenerUltimoRegistroKilometraje($idVehiculo)
{
    include 'conexion.php';
    // Mismo criterio: Fecha más reciente e ID más alto
    $sql = "SELECT cantKilometraje FROM historial_kilometraje_moviles 
            WHERE idMovil = " . intval($idVehiculo) . " 
            ORDER BY fechaRegistro DESC, idKilometraje DESC LIMIT 1";

    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);

    // Retornamos 0 si la tabla está vacía para ese móvil
    return ($dato) ? $dato['cantKilometraje'] : 0;
}

function getIDCategoriaSistemaCCTV()
{

    include 'conexion.php';
    $sql = "SELECT idCategoria FROM categorias_equipos WHERE nomCategoria = 'Sistema CCTV'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCategoria'];
}

function getIDCategoriaSistemaCCAA()
{

    include 'conexion.php';
    $sql = "SELECT idCategoria FROM categorias_equipos WHERE nomCategoria = 'SISTEMA DE CONTROL DE ACCESO'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCategoria'];
}

function getIDCategoriaSistemaBMS()
{
    include 'conexion.php';
    $sql = "SELECT idCategoria FROM categorias_equipos WHERE nomCategoria = 'BMS'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCategoria'];
}

function getCantEquipoHT($idOrden)
{

    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS total FROM equipos_recepcion_trabajo WHERE idOrden = " . $idOrden;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function getMedidorAguaHBQP()
{

    include 'conexion.php';
    $sql = "SELECT idCliente FROM cliente_agua WHERE tipoHospital = 'HBQP' LIMIT 1";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCliente'];
}

function getMedidorAguaHSMQ()
{
    include 'conexion.php';
    $sql = "SELECT idCliente FROM cliente_agua WHERE tipoHospital = 'HSMQ' LIMIT 1";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCliente'];
}

function getNomMedidorAgua($id)
{

    include 'conexion.php';
    $sql = "SELECT medidor FROM cliente_agua WHERE idCliente = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['medidor'];
}

function getNumClienteAgua($id)
{

    include 'conexion.php';
    $sql = "SELECT nCliente FROM cliente_agua WHERE idCliente = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nCliente'];
//    return $sql;
}


function existeClienteAgua($nCliente)
{

    include 'conexion.php';
    $sql = "SELECT idCliente FROM cliente_agua WHERE nCliente = '" . $nCliente . "'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCliente'];
}

function existeClienteLuz($nCliente)
{
    include 'conexion.php';
    $sql = "SELECT idCliente FROM cliente_electricidad WHERE nCliente = '" . $nCliente . "'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCliente'];
}

function existeMedidorLuz($medidor)
{
    include 'conexion.php';
    $sql = "SELECT idCliente FROM cliente_electricidad WHERE medidor = '" . $medidor . "'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCliente'];
}

function existeMedidorAgua($medidor)
{

    include 'conexion.php';
    $sql = "SELECT idCliente FROM cliente_agua WHERE medidor = '" . $medidor . "'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idCliente'];
}

function buscarFechaActa($idActa)
{

    include 'conexion.php';
    $sql = "SELECT fechaActa FROM actas WHERE idActa = " . $idActa;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['fechaActa'];
}

function getNomCategoriaEquipamiento($idCategoria)
{

    include 'conexion.php';
    $sql = "SELECT nomCategoria FROM categorias_equipos WHERE idCategoria = " . $idCategoria;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomCategoria'];
}

function buscarRUTConductor($rut)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM conductores_movilizacion WHERE rutConductor LIKE '" . $rut . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function buscarRUTConductor2($rut)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM conductores_movilizacion WHERE rutConductor LIKE '" . $rut . "' and antesConductor = 2";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function contarSubTareaP($idTareaP)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM tareas_carta_gantt WHERE idPadreTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION

function contarObsPlan($idTareaP)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM observaciones_carta_gantt WHERE idTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarTotalPorcentaje($idTareaP)
{
    include('conexion.php');
    $query = "SELECT SUM(porcentajeTareaP) AS totalPorcentaje FROM tareas_carta_gantt WHERE idPadreTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalPorcentaje'];
}

function obtenerPorcentajeTarea($idTareaP)
{
    include('conexion.php');
    $query = "SELECT porcentajeTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['porcentajeTareaP'];
}

function obtenerIDparaCotInventario()
{
    include('conexion.php');
    $query = "SELECT MAX(idArchivo) AS ultimo_id FROM archivos_cotizacion_inventario;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $ultimo_id = $dato['ultimo_id'];
    if ($ultimo_id > 0) {
        $datoMandar = $ultimo_id + 1;
    } else {
        $datoMandar = 1;
    }

    return $datoMandar;
}

// FIN FUNCION

function contarCantidadPadre($idTareaP)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS totalSubPadre FROM tareas_carta_gantt WHERE idPadreTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalSubPadre'];
}

// FIN FUNCION

function getTareaPlan($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM tareas_carta_gantt WHERE idTareaP = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idTareaP'] = $row['idTareaP'];
        $datos['actividadTareaP'] = $row['actividadTareaP'];
        $datos['inicioPlanTareaP'] = $row['inicioPlanTareaP'];
        $datos['finPlanTareaP'] = $row['finPlanTareaP'];
        $datos['diasPlanTareaP'] = $row['diasPlanTareaP'];
        $datos['tipoDiasHabil'] = $row['tipoDiasHabil'];
        $datos['inicioRealTareaP'] = $row['inicioRealTareaP'];
        $datos['finRealTareaP'] = $row['finRealTareaP'];
        $datos['porcentajeTareaP'] = $row['porcentajeTareaP'];
        $datos['idPadreTareaP'] = $row['idPadreTareaP'];
        $datos['iniciadaTareaP'] = $row['iniciadaTareaP'];
        $datos['finalizadaTareaP'] = $row['finalizadaTareaP'];
        $datos['nomCreadorTareaP'] = $row['nomCreadorTareaP'];
        $datos['deptoCreadorTareaP'] = $row['deptoCreadorTareaP'];
        $datos['idEquipamiento'] = $row['idEquipamiento'];
        $datos['funcionariosSeleccionados'] = $row['funcionariosSeleccionados'];
        return $datos;
    }
}

// FIN FUNCION

function tienePadreTarea($id)
{
    include('conexion.php');
    $query = "SELECT idPadreTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idPadreTareaP'];
}

function obtenerNomUCA($id)
{
    include 'conexion.php';
    $sql = "SELECT name FROM ucas_listado WHERE idUnique = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['name'];
}

function obtenerNomPuerta2($id)
{
    include 'conexion.php';
    $sql = "SELECT name FROM acs_door WHERE idUnique = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['name'];
}

function obtenerNomPuerta($deviceid, $doorid)
{
    include 'conexion.php';
    $sql = "SELECT name FROM acs_door WHERE idDevice = " . $deviceid . " AND no = " . $doorid;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['name'];
}

function obtenerNomPuertaUCA($deviceid)
{
    include 'conexion.php';
    $sql = "SELECT name FROM acs_door WHERE idDevice = " . $deviceid;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['name'];
}

function isJefe($nomJefe)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS total FROM jefes_tecnicos WHERE userJefe = '" . $nomJefe . "'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function isAdmin($userAdministrador)
{
    include 'conexion.php';

    // Escapar correctamente para evitar inyección SQL
    $user = mysqli_real_escape_string($conexion, trim($userAdministrador));

    $sql = "SELECT 1 FROM administracion WHERE userAdministrador = '$user' LIMIT 1";
    $res = mysqli_query($conexion, $sql);

    return ($res && mysqli_num_rows($res) > 0);
}

function obtenerIDUCA($name)
{
    include 'conexion.php';
    $sql = "SELECT idUnique FROM ucas_listado WHERE name = " . $name;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idUnique'];
}

function getRUTConductor($id)
{
    include('conexion.php');
    $query = "SELECT rutConductor FROM conductores_movilizacion WHERE idConductor = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutConductor'];
}

function getRUTConductor2($id)
{
    include('conexion.php');
    $query = "SELECT rutConductor FROM conductores_movilizacion_vamoos_spa WHERE idConductor = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutConductor'];
}

function getTipoMovil($numPatente)
{
    include('conexion.php');
    $query = "SELECT tipoMovil FROM patentes_movilizacion WHERE numPatente = '$numPatente' AND patentesactivas = 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['tipoMovil'];
}

function getTipoMovilSpa($numPatente)
{
    include('conexion.php');
    $query = "SELECT tipoMovil FROM patentes_movilizacion WHERE numPatente = '$numPatente' AND patentesactivas = 2";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['tipoMovil'];
}

function getAnoMovil($numPatente)
{
    include('conexion.php');
    $query = "SELECT anoVehiculo FROM patentes_movilizacion WHERE numPatente = '$numPatente' AND patentesactivas = 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['anoVehiculo'];
}

function getAnoMovilSpa($numPatente)
{
    include('conexion.php');
    $query = "SELECT anoVehiculo FROM patentes_movilizacion WHERE numPatente = '$numPatente' AND patentesactivas = 2";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['anoVehiculo'];
}

// FIN FUNCION

function getNomConductor($id)
{
    include('conexion.php');
    $query = "SELECT nomConductor FROM conductores_movilizacion WHERE idConductor = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomConductor'];
}

// FIN FUNCION

function getPuertasFormulario($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM formularios_acceso WHERE idFormCCAA = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idFormCCAA'] = $row['idFormCCAA'];
        $datos['unidadFormCCAA'] = $row['unidadFormCCAA'];
        $datos['fechaFormCCAA'] = $row['fechaFormCCAA'];
        $datos['subdireccionFormCCAA'] = $row['subdireccionFormCCAA'];
        $datos['cantidadFormCCAA'] = $row['cantidadFormCCAA'];
        $datos['door1FormCCAA'] = $row['door1FormCCAA'];
        $datos['door2FormCCAA'] = $row['door2FormCCAA'];
        $datos['door3FormCCAA'] = $row['door3FormCCAA'];
        $datos['door4FormCCAA'] = $row['door4FormCCAA'];
        $datos['door5FormCCAA'] = $row['door5FormCCAA'];
        $datos['door6FormCCAA'] = $row['door6FormCCAA'];
        $datos['door7FormCCAA'] = $row['door7FormCCAA'];
        $datos['door8FormCCAA'] = $row['door8FormCCAA'];
        $datos['door9FormCCAA'] = $row['door9FormCCAA'];
        $datos['door10FormCCAA'] = $row['door10FormCCAA'];
        $datos['door11FormCCAA'] = $row['door11FormCCAA'];
        $datos['door12FormCCAA'] = $row['door12FormCCAA'];
        $datos['door13FormCCAA'] = $row['door13FormCCAA'];
        $datos['door14FormCCAA'] = $row['door14FormCCAA'];
        $datos['door15FormCCAA'] = $row['door15FormCCAA'];
        $datos['door16FormCCAA'] = $row['door16FormCCAA'];
        $datos['door17FormCCAA'] = $row['door17FormCCAA'];
        $datos['door18FormCCAA'] = $row['door18FormCCAA'];
        return $datos;
    }
}

function obtenerCalidadJuridica($rut)
{
    $rut2 = quitarCaracteresRUT($rut);
    include('conexion.php');
    $query = 'SELECT calidadContractual FROM actas WHERE rutTarjeta ="' . $rut2 . '" ORDER BY fechaActa DESC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);

    if (!empty($dato['calidadContractual'])) {
        return $dato['calidadContractual'];
    } else {
        return "Sin Información";
    }
}

// FIN FUNCION

function getUnidadFormatosCCAA($id)
{
    include('conexion.php');
    $query = 'SELECT unidadFormCCAA FROM formularios_acceso WHERE idFormCCAA  = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['unidadFormCCAA'];
}

function getDataRegistroForm($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM formularios_registro WHERE idFormRegAcceso = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idFormRegAcceso'] = $row['idFormRegAcceso'];
        $datos['folioFormRegAcceso'] = $row['folioFormRegAcceso'];
        $datos['unidadFormRegAcceso'] = $row['unidadFormRegAcceso'];
        $datos['fechaFormRegAcceso'] = $row['fechaFormRegAcceso'];
        $datos['fechaRecepcionFormRegAcceso'] = $row['fechaRecepcionFormRegAcceso'];
        $datos['categoriaFormRegAcceso'] = $row['categoriaFormRegAcceso'];
        $datos['solicitanteFormRegAcceso'] = $row['solicitanteFormRegAcceso'];
        $datos['unidadSolicitaFormRegAcceso'] = $row['unidadSolicitaFormRegAcceso'];
        $datos['emailSolicitaFormRegAcceso'] = $row['emailSolicitaFormRegAcceso'];
        $datos['tipoDocumento'] = $row['tipoDocumento'];
        $datos['numeroDocumento'] = $row['numeroDocumento'];
        return $datos;
    }
}

function getCategoriaFormRegAcceso($id)
{
    include('conexion.php');
    $query = 'SELECT categoriaFormRegAcceso FROM formularios_registro WHERE idFormRegAcceso  = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['categoriaFormRegAcceso'];
}

function getFolioFormRegAcceso($id)
{
    include('conexion.php');
    $query = 'SELECT folioFormRegAcceso FROM formularios_registro WHERE idFormRegAcceso  = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['folioFormRegAcceso'];
}

function getFechaFormRegAcceso($id)
{
    include('conexion.php');
    $query = 'SELECT fechaFormRegAcceso FROM formularios_registro WHERE idFormRegAcceso  = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['fechaFormRegAcceso'];
}


function quitarCaracteresRUT($rut)
{
    // Eliminar puntos y guiones del RUT
    $rut = str_replace(array('.', '-'), '', $rut);

    // Reemplazar 'K' por '0'
    $rut = str_replace('K', '0', $rut);
    $rut = str_replace('k', '0', $rut);
    if (strlen($rut) <= 8) {
        $rut = '0' . $rut;
    }

    return $rut;
}

function buscarRUTenActa($rut)
{
    include 'conexion.php';
    $sql = "SELECT idActa FROM actas WHERE rutTarjeta = '" . $rut . "' ORDER BY idActa DESC LIMIT 1";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idActa'];
}

function buscarRUTenControlAcceso($rut)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM ACM_owner WHERE OWNER_CODE  = '" . $rut . "'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function obtenerIDporRUTCA($rut)
{
    include 'conexion.php';
    $sql = "SELECT ID FROM ACM_owner WHERE OWNER_CODE  = '" . $rut . "' LIMIT 1";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['ID'];
}

function obtenerRutaControlAcceso($rut)
{
    include 'conexion.php';
    $sql = "SELECT DEPT_ID FROM ACM_owner WHERE OWNER_CODE  = '" . $rut . "'";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    $DEPT_ID = $dato['DEPT_ID'];

    $nomDepartamento = getNomDepartamentoCCAA($DEPT_ID);
    return $nomDepartamento;
}

function verificarYFormatearRUT($rut)
{
    $rut = str_replace(array('.', '-'), '', strtoupper($rut));

    // Verificar que el RUT tenga un formato válido
    if (!preg_match('/^[0-9]{1,8}[0-9K]$/', $rut)) {
        return false; // RUT no válido
    }

    $numero = substr($rut, 0, -1);
    $verificador = $rut[strlen($rut) - 1];

    // Calcular el dígito verificador esperado
    $factor = 2;
    $suma = 0;

    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $suma += $numero[$i] * $factor;
        $factor = $factor % 7 == 0 ? 2 : $factor + 1;
    }

    $digitoVerificadorEsperado = 11 - ($suma % 11);

    if ($digitoVerificadorEsperado == 11) {
        $digitoVerificadorEsperado = 0;
    } elseif ($digitoVerificadorEsperado == 10) {
        $digitoVerificadorEsperado = 'K';
    }

    if ($verificador != $digitoVerificadorEsperado) {
        // Formatear el RUT con puntos y guion
        $formattedRUT = number_format($numero, 0, '', '.');
        $formattedRUT .= '-' . $digitoVerificadorEsperado;

        return $formattedRUT;
    }

    // Formatear el RUT con puntos y guion
    $formattedRUT = number_format($numero, 0, '', '.');
    $formattedRUT .= '-' . $verificador;

    return $formattedRUT;
}

function contarFormulariosGuardados($id)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM formularios_documentacion WHERE idFormRegAcceso = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerRutaFormFirma($id)
{
    include 'conexion.php';
    $sql = "SELECT rutaDocForm FROM formularios_documentacion WHERE idFormRegAcceso = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaDocForm'];
}

function obtenerRutasFormFirma($id)
{

    include 'conexion.php';
    $sql = "SELECT nomDocForm, rutaDocForm, fechaDocForm
            FROM formularios_documentacion 
            WHERE idFormRegAcceso = $id ORDER BY fechaDocForm DESC";

    $res = mysqli_query($conexion, $sql);
    $documentos = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $documentos[] = [
            'nombre' => $row['nomDocForm'],
            'ruta' => $row['rutaDocForm'],
            'fecha' => $row['fechaDocForm']
        ];
    }

    return $documentos;
}

function contarFuncionariosPermisoRegistro($id)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM formularios_funcionarios WHERE permisosFormFuncCCAA = 1 AND idFormRegAcceso = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarFuncionariosPermisoRegistroTodos($id)
{
    include 'conexion.php';
    $sql = "SELECT permisosFormFuncCCAA,COUNT(*) as total FROM formularios_funcionarios WHERE permisosFormFuncCCAA = 1 AND idFormRegAcceso = $id GROUP BY permisosFormFuncCCAA";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarFuncionariosTotalRegistro($id)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM formularios_funcionarios WHERE idFormRegAcceso = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarFuncionariosRegBloqueo($id)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM formularios_funcionarios_bloqueo WHERE estadoFormRegBloqueo = 1 AND idFormRegBloqueo = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarFuncionariosTotalBloqueo($id)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM formularios_funcionarios_bloqueo WHERE idFormRegBloqueo = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarObservacionesIngresadas()
{
    include 'conexion.php';
    $contabilizarRecibidos = 0;
    $sqlObtener = "SELECT * FROM formularios_observaciones ORDER BY fechaOBS ASC";
    $resObtener = mysqli_query($conexion, $sqlObtener);
    while ($rowObtener = $resObtener->fetch_assoc()) {

        $idObservacion = $rowObtener['idObservacion'];
    }
}


function getNombreFuncionarioFormulario($rutFunc)
{
    include 'conexion.php';
    $sql = "SELECT nomFormFuncCCAA FROM formularios_funcionarios WHERE rutFormFuncCCAA LIKE '%" . $rutFunc . "%' ORDER BY fechaFormCCAA DESC LIMIT 1";
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomFormFuncCCAA'];
}

function getDataFuncionarioForm($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM formularios_funcionarios WHERE idFormFuncCCAA = ' . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idFormFuncCCAA'] = $row['idFormFuncCCAA'];
        $datos['nomFormFuncCCAA'] = $row['nomFormFuncCCAA'];
        $datos['rutFormFuncCCAA'] = $row['rutFormFuncCCAA'];
        $datos['calidadFormFuncCCAA'] = $row['calidadFormFuncCCAA'];
        $datos['unidadFormFuncCCAA'] = $row['unidadFormFuncCCAA'];
        $datos['fechaFormCCAA'] = $row['fechaFormCCAA'];
        for ($i = 1; $i < 19; $i++) {
            $datos['door' . $i . 'FormCCAA'] = $row['door' . $i . 'FormCCAA'];
        }
        $datos['idFormRegAcceso'] = $row['idFormRegAcceso'];
        $datos['verificaActaFormFuncCCAA'] = $row['verificaActaFormFuncCCAA'];
        $datos['fechaActaFormFuncCCAA'] = $row['fechaActaFormFuncCCAA'];
        $datos['verificaCAFormFuncCCAA'] = $row['verificaCAFormFuncCCAA'];
        $datos['fechaVerCAFormFuncCCAA'] = $row['fechaVerCAFormFuncCCAA'];
        $datos['permisosFormFuncCCAA'] = $row['permisosFormFuncCCAA'];
        $datos['fechaPermisoFormFuncCCAA'] = $row['fechaPermisoFormFuncCCAA'];
        return $datos;
    }
}

function getNomPuertaCCAA($num, $id)
{
    include('conexion.php');
    $query = 'SELECT door' . $num . 'FormCCAA AS nomPuerta FROM formularios_acceso WHERE idFormCCAA  = ' . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nomPuerta'];
}

function getFuncionarioACM($OWNER_CODE)
{
    include('conexion.php');
    $query = 'SELECT * FROM ACM_owner WHERE OWNER_CODE = "' . $OWNER_CODE . '" ORDER BY START_DATE DESC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['ID'] = $row['ID'];
        $datos['OWNER_CODE'] = $row['OWNER_CODE'];
        $datos['OWNER_Name'] = $row['OWNER_Name'];
        $datos['DEPT_ID'] = $row['DEPT_ID'];
        $datos['nomDepartament'] = buscarNomDeptoCCAA($row['DEPT_ID']);
        $datos['CARD_NUMBER'] = $row['CARD_NUMBER'];
        return $datos;
    } else {
        return 'No Existe';
    }
}

function getFuncionarioACMxID($id)
{
    include('conexion.php');
    $query = 'SELECT * FROM ACM_owner WHERE ID = "' . $id . '" ORDER BY START_DATE DESC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['ID'] = $row['ID'];
        $datos['OWNER_CODE'] = $row['OWNER_CODE'];
        $datos['OWNER_Name'] = $row['OWNER_Name'];
        $datos['DEPT_ID'] = $row['DEPT_ID'];
        $datos['nomDepartament'] = buscarNomDeptoCCAA($row['DEPT_ID']);
        $datos['CARD_NUMBER'] = $row['CARD_NUMBER'];
        return $datos;
    } else {
        return 'No Existe';
    }
}

function getCategoriaRegistro($id)
{
    include 'conexion.php';
    $sql = "SELECT categoriaFormRegAcceso FROM formularios_registro WHERE idFormRegAcceso = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['categoriaFormRegAcceso'];
}

function getObsBitacora($id)
{
    include('conexion.php');
    $query = "SELECT observacionBitacora FROM bitacora WHERE idBitacora = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['observacionBitacora'];
}

// FIN FUNCION

function getBitacora($id)
{
    include('conexion.php');
    $query = "SELECT * FROM bitacora WHERE idBitacora = " . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idBitacora'] = $row['idBitacora'];
        $datos['observacionBitacora'] = $row['observacionBitacora'];
        $datos['fechaBitacora'] = $row['fechaBitacora'];
        $datos['tecnicoBitacora'] = $row['tecnicoBitacora'];
        $datos['idSubcategoria'] = $row['idSubcategoria'];
        return $datos;
    } else {
        return 'null';
    }
}

// FIN FUNCION


function contarFormulariosRecibidos()
{
    include 'conexion.php';

    // Consulta SQL optimizada:
    // - Cuenta formularios cuyo conteo de bloqueo o permisos no coincide con su total
    // - Usa subconsultas agrupadas para cada caso
    $sql = "
        SELECT COUNT(*) AS total_recibidos
        FROM formularios_registro fr
        WHERE 
            (
                -- Comparar bloqueos: si hay menos registros activos que totales
                (
                    (SELECT COUNT(*) 
                     FROM formularios_funcionarios_bloqueo fb 
                     WHERE fb.idFormRegBloqueo = fr.idFormRegAcceso 
                     AND fb.estadoFormRegBloqueo = 1)
                    <
                    (SELECT COUNT(*) 
                     FROM formularios_funcionarios_bloqueo fb2 
                     WHERE fb2.idFormRegBloqueo = fr.idFormRegAcceso)
                )
                OR
                -- Comparar permisos: si hay menos permisos aprobados que totales
                (
                    (SELECT COUNT(*) 
                     FROM formularios_funcionarios ff 
                     WHERE ff.idFormRegAcceso = fr.idFormRegAcceso 
                     AND ff.permisosFormFuncCCAA = 1)
                    <
                    (SELECT COUNT(*) 
                     FROM formularios_funcionarios ff2 
                     WHERE ff2.idFormRegAcceso = fr.idFormRegAcceso)
                )
            )
    ";

    $res = mysqli_query($conexion, $sql);

    if ($res && $row = mysqli_fetch_assoc($res)) {
        return (int)$row['total_recibidos'];
    }

    return 0; // En caso de error
}

function getFechaUltimoFuncionario($id)
{
    include('conexion.php');
    $query = 'SELECT fechaBloqueoFormFunc FROM formularios_funcionarios_bloqueo WHERE idFormRegBloqueo  = ' . $id . ' ORDER BY fechaBloqueoFormFunc DESC LIMIT 1';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['fechaBloqueoFormFunc'];
}

function getIDFormBloqueo($id)
{
    include 'conexion.php';
    $sql = "SELECT idFormRegBloqueo FROM formularios_funcionarios_bloqueo WHERE idFormFuncCCAA  = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idFormRegBloqueo'];
}

function compararPorFecha($a, $b)
{
    return strtotime($a['fechaOrden']) - strtotime($b['fechaOrden']);
}


function sumarDiasHabilFinal($fechaInicio, $diasHabiles, $diasFeriados)
{
    $fecha = new DateTime($fechaInicio);

    // Iterar hasta alcanzar la cantidad de días hábiles
    while ($diasHabiles > 0) {
        // Avanzar un día
        $fecha->modify('+1 day');

        // Excluir fines de semana (sábado = 6, domingo = 0)
        if ($fecha->format('N') < 6) {
            // Excluir días feriados
            if (!in_array($fecha->format('Y-m-d'), $diasFeriados)) {
                $diasHabiles--;
            }
        }
    }

    return $fecha->format('Y-m-d');
}

function obtenerFeriadosChile()
{
    $feriadosChile = array(
        '2023-01-01', // Año Nuevo
        '2023-01-02', // Año Nuevo 2
        '2023-04-07', // Viernes Santo
        '2023-04-08', // Sabado Santo
        '2023-05-01', // Día del Trabajo
        '2023-05-07', // Día Elecciones
        '2023-05-21', // Día de las Glorias Navales
        '2023-06-21', // Día de Pueblos Indigenas
        '2023-06-26', // Día San Pedro y San Pablo
        '2023-07-16', // Día Virgen del Carmen
        '2023-08-15', // Asunción de la Virgen
        '2023-09-18', // Fiestas Patrias
        '2023-09-19', // Día de las Glorias del Ejército
        '2023-10-09', // Encuentro de 2 Mundos
        '2023-10-27', // Día de Iglesias Evangelicas
        '2023-11-01', // Día de Todos Los Santos
        '2023-12-08', // Inmaculada Concepción
        '2023-12-17', // Plebiscito
        '2023-12-25', // Navidad
        //Año 2024
        '2024-01-01', // Año Nuevo
        '2024-03-29', // Viernes Santo
        '2024-03-30', // Sabado Santo
        '2024-05-01', // Día del Trabajo
        '2024-05-21', // Día de las Glorias Navales
        '2024-06-09', // Día Elecciones
        '2024-06-20', // Día de Pueblos Indigenas
        '2024-06-29', // Día San Pedro y San Pablo
        '2024-07-16', // Día Virgen del Carmen
        '2024-08-15', // Asunción de la Virgen
        '2024-09-18', // Fiestas Patrias
        '2024-09-19', // Día de las Glorias del Ejército
        '2024-09-20', // Feriado Adicional
        '2024-10-12', // Encuentro de 2 Mundos
        '2024-10-27', // Elecciones Municipales
        '2024-10-31', // Día de Iglesias Evangelicas
        '2024-11-01', // Día de Todos Los Santos
        '2024-11-24', // 2da Elecciones Municipales
        '2024-12-08', // Inmaculada Concepción
        '2024-12-25', // Navidad
        //Año 2025
        '2025-01-01', // Año Nuevo
        '2025-04-18', // Viernes Santo
        '2025-04-19', // Sabado Santo
        '2025-05-01', // Día del Trabajo
        '2025-05-21', // Día de las Glorias Navales
        '2025-06-20', // Día de Pueblos Indigenas
        '2025-06-29', // Día San Pedro y San Pablo y Elecciones Primarias
        '2025-07-16', // Día Virgen del Carmen
        '2025-08-15', // Asunción de la Virgen
        '2025-09-18', // Fiestas Patrias
        '2025-09-19', // Día de las Glorias del Ejército
        '2025-10-12', // Encuentro de 2 Mundos
        '2025-10-31', // Día de Iglesias Evangelicas
        '2025-11-01', // Día de Todos Los Santos
        '2025-11-16', // Elecciones Presidenciales
        '2025-12-08', // Inmaculada Concepción
        '2025-12-14', // Elecciones Presidenciales
        '2025-12-25', // Navidad
        //Año 2026
        '2026-01-01', // Año Nuevo
        '2026-04-03', // Viernes Santo
        '2026-04-04', // Sabado Santo
        '2026-05-01', // Día del Trabajo
        '2026-05-21', // Día de las Glorias Navales
        '2026-06-21', // Día Nacional de los Pueblos Indígenas
        '2026-06-29', // San Pedro y San Pablo
        '2026-07-16', // Día de la Virgen del Carmen
        '2026-08-15', // Asunción de la Virgen
        '2026-09-18', // Fiestas Patrias
        '2026-09-19', // Día de las Glorias del Ejército
        '2026-10-12', // Encuentro de 2 Mundos
        '2026-10-31', // Día de Iglesias Evangelicas
        '2026-11-01', // Día de Todos Los Santos
        '2026-12-08', // Inmaculada Concepción
        '2026-12-25', // Navidad

    );
    return $feriadosChile;
}

function sumarDiasCorridos($fechaInicio, $diasASumar)
{
    $fecha = new DateTime($fechaInicio);
    $intervalo = new DateInterval('P' . $diasASumar . 'D');
    $fecha->add($intervalo);

    return $fecha->format('Y-m-d');
}

function obtenerIDPadreTarea($id)
{
    include 'conexion.php';
    $sql = "SELECT idPadreTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $id;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['idPadreTareaP'];
}

function getFechaInicio($idTareaP)
{
    include('conexion.php');
    $query = "SELECT inicioPlanTareaP FROM tareas_carta_gantt WHERE idTarea = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['inicioPlanTareaP'];
}

// FIN FUNCION


function buscarTareasHijas($idPadreTareaP, $fechaInicioPlan, $fechaFinalPlan, $nivel = 0)
{

    include('conexion.php');
    // Establecer la conexión a la base de datos
    // Verificar la conexión
    // Consulta para obtener las tareas con el mismo idPadreTareaP
    $consultaBuscar = "SELECT * FROM tareas_carta_gantt WHERE idPadreTareaP = " . $idPadreTareaP;
    // Ejecutar la consulta de búsqueda

    $resPlan = mysqli_query($conexion, $consultaBuscar);

    // Verificar si hay resultados
    if ($resPlan->num_rows > 0) {
        // Manejar los resultados obtenidos
        while ($fila = $resPlan->fetch_assoc()) {
            // Imprimir información o realizar otras operaciones según sea necesario
            // Llamada recursiva para buscar las tareas hijas de manera recursiva
            if ($fechaInicioPlan > $fila['inicioPlanTareaP']) {
                echo 'error<br><br>';
            } else {
                echo 'exito<br><br>';
            }
            buscarTareasHijas($fila['idTareaP'], $fechaInicioPlan, $fechaFinalPlan, $nivel + 1);
        }
    }
    $conexion->close();
}

function obtenerDiasHabiles($fechaInicio, $fechaFin, $feriados)
{
    $diasHabiles = 0;
    $fechaActual = new DateTime($fechaInicio);
    $fechaTermino = new DateTime($fechaFin);

    // Iterar sobre cada día entre las fechas
    while ($fechaActual <= $fechaTermino) {
        // Verificar si el día actual no es un feriado
        if (!in_array($fechaActual->format('Y-m-d'), $feriados) && $fechaActual->format('N') < 6) {
            $diasHabiles++;
        }

        // Avanzar al siguiente día
        $fechaActual->modify('+1 day');
    }

    return $diasHabiles;
}

function obtenerDiasCorridos($fechaInicio, $fechaFin)
{
    // Crear objetos DateTime para las fechas de inicio y término
    $inicio = new DateTime($fechaInicio);
    $fin = new DateTime($fechaFin);

    // Calcular la diferencia entre las fechas

    if ($inicio == $fin) {
        $diferenciaDias = 1;
    } else {

        $diferencia = $inicio->diff($fin);

        // Obtener la diferencia en días
        $diferenciaDias = $diferencia->days;
    }

    return $diferenciaDias;
}

function getInicioTareaMin($idTareaP)
{
    include('conexion.php');
    $query = "SELECT inicioPlanTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP . " ORDER BY inicioPlanTareaP DESC LIMIT 1 ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (empty($dato)) {
        return null;
    } else {
        return $dato['inicioPlanTareaP'];
    }
}

// FIN FUNCION

function getInicioTareaMax($idTareaP)
{
    include('conexion.php');
    $query = "SELECT inicioPlanTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP . " ORDER BY inicioPlanTareaP DESC LIMIT 1 ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (empty($dato)) {
        return null;
    } else {
        return $dato['inicioPlanTareaP'];
    }
}

// FIN FUNCION

function getTerminoTareaMax($idTareaP)
{
    include('conexion.php');
    $query = "SELECT finPlanTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP . " ORDER BY finPlanTareaP DESC LIMIT 1 ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (empty($dato)) {
        return 'dd';
    } else {
        return $dato['finPlanTareaP'];
    }
}

// FIN FUNCION

function getInicioPadreTareaMin($idTareaP)
{
    include('conexion.php');
    $query = "SELECT inicioPlanTareaP FROM tareas_carta_gantt WHERE idPadreTareaP = " . $idTareaP . " ORDER BY inicioPlanTareaP ASC LIMIT 1 ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (empty($dato)) {
        $query2 = "SELECT inicioPlanTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP . " ORDER BY inicioPlanTareaP ASC LIMIT 1 ";
        $res2 = mysqli_query($conexion, $query2);
        $dato2 = mysqli_fetch_assoc($res2);
        if (empty($dato2)) {
            return null;
        } else {
            return $dato2['inicioPlanTareaP'];
        }
    } else {
        return $dato['inicioPlanTareaP'];
    }
}

// FIN FUNCION

function getInicioPadreTareaMax($idTareaP)
{
    include('conexion.php');
    $query = "SELECT inicioPlanTareaP FROM tareas_carta_gantt WHERE idPadreTareaP = " . $idTareaP . " ORDER BY inicioPlanTareaP DESC LIMIT 1 ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (empty($dato)) {
        return null;
    } else {
        return $dato['inicioPlanTareaP'];
    }
}

// FIN FUNCION

function getTerminoPadreTareaMax($idTareaP)
{
    include('conexion.php');
    $query = "SELECT finPlanTareaP FROM tareas_carta_gantt WHERE idPadreTareaP = " . $idTareaP . " ORDER BY finPlanTareaP DESC LIMIT 1 ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    if (empty($dato)) {
        return null;
    } else {
        return $dato['finPlanTareaP'];
    }
}

// FIN FUNCION


function getTareaIniciada($idTareaP)
{
    include('conexion.php');
    $query = "SELECT iniciadaTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['iniciadaTareaP'];
}

function getPorcentajeTareaP($idTareaP)
{
    include('conexion.php');
    $query = "SELECT porcentajeTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['porcentajeTareaP'];
}

function actualizarPorcentajeTareas($idTareaP)
{
    include('conexion.php');
    $contarTotalPorcentaje = contarTotalPorcentaje($idTareaP);

    $contarCantidadPadre = contarCantidadPadre($idTareaP);

    if ($contarTotalPorcentaje > 0) {
        $porcentajePadre = $contarTotalPorcentaje / $contarCantidadPadre;
    } else {
        $porcentajePadre = 0;
    }


    $query = "UPDATE tareas_carta_gantt SET porcentajeTareaP = " . $porcentajePadre . " WHERE idTareaP = " . $idTareaP;
    $res2 = mysqli_query($conexion, $query);

    if ($res2) {
        return $porcentajePadre;
        // return $query2;
    } else {
        return "Error";
    }
}


function contarTotalTareaP()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM tareas_carta_gantt";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION


function obtenerDeptoCreadorTarea($idTareaP)
{
    include('conexion.php');
    $query = "SELECT deptoCreadorTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

// FIN FUNCION


function obtenerNomTareaP($idTareaP)
{
    include('conexion.php');
    $query = "SELECT actividadTareaP FROM tareas_carta_gantt WHERE idTareaP = " . $idTareaP;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $actividadTareaP = $dato['actividadTareaP'];

    if (empty($actividadTareaP)) {
        return '';
    } else {
        return $actividadTareaP;
    }
}

// FIN FUNCION

function getACSGroupDoor($id)
{
    include('conexion.php');
    $query = "SELECT * FROM ACS_DOOR_GROUP WHERE DoorGroupID = " . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['KID'] = $row['KID'];
        $datos['DoorGroupID'] = $row['DoorGroupID'];
        $datos['DoorGroupName'] = $row['DoorGroupName'];
        return $datos;
    } else {
        return 'null';
    }
}

// Función para agregar archivos al zip
function agregar_zipSC($dir, $zip)
{
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . $file)) {
                        agregar_zipSC($dir . $file . '/', $zip);
                    } else {
                        $zip->addFile($dir . $file, $file);
                    }
                }
            }
            closedir($dh);
        }
    }
}

function verificarEsPabellon($id)
{
    include('conexion.php');
    $query = "SELECT isPabellon FROM equipos WHERE idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['isPabellon'];
}

// FIN FUNCION

function cambiarNomPabellon($id)
{

    include('conexion.php');
    $query = "SELECT * FROM equipos WHERE idEquipo = " . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $nomEquipo = '';
        if ($row['equipoPabellon'] > 0) {
            $nomEquipo = $row['nomEquipo'] . '<br>( Pabellon ' . $row['equipoPabellon'] . ' )';
        } else {
            $nomEquipo = $row['nomEquipo'];
        }
        $nomEquipo = mb_convert_case($nomEquipo, MB_CASE_UPPER, 'UTF-8');
        return $nomEquipo;
    } else {
        return 'Error al Obtener Información';
    }

}

function contarRonda($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM parametros_ot_rondas WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarDetalleBMS($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(detalleTG) AS TOTAL FROM trabajos_general WHERE idTG = $idTG AND detalleTG != ''";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}


function contarDetalleCamara($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(detalleTurnoCamara) AS TOTAL FROM trabajos_general WHERE idTG = $idTG AND detalleTurnoCamara != ''";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarDetalleLLE($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(detalleTurnoLLE) AS TOTAL FROM trabajos_general WHERE idTG = $idTG AND detalleTurnoLLE != ''";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarDetalleCA($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(detalleTurnoCA) AS TOTAL FROM trabajos_general WHERE idTG = $idTG AND detalleTurnoCA != ''";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarDetalleOTROS($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(detalleOtros) AS TOTAL FROM trabajos_general WHERE idTG = $idTG AND detalleOtros != ''";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarRespuestaBMS($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'BMS'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarRespuestaCCTV($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CCTV'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarRespuestaLLE($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'LLE'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarRespuestaCA($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CA'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarRespuestaOtros($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'OTROS'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}


function avancesbms($idTG)
{
    include('conexion.php');
    $query = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'BMS'";
    $res = mysqli_query($conexion, $query);
    $avance = '';
    while ($row = $res->fetch_assoc()) {
        $avance = $row['detalleObs'];
    }
    return $avance;
}

function avanceslle($idTG)
{
    include('conexion.php');
    $query = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'LLE'";
    $res = mysqli_query($conexion, $query);
    $avance = '';
    while ($row = $res->fetch_assoc()) {
        $avance = $row['detalleObs'];
    }
    return $avance;
}

function avancesca($idTG)
{
    include('conexion.php');
    $query = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CA'";
    $res = mysqli_query($conexion, $query);
    $avance = '';
    while ($row = $res->fetch_assoc()) {
        $avance = $row['detalleObs'];
    }
    return $avance;
}

function avancescctv($idTG)
{
    include('conexion.php');
    $query = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CCTV'";
    $res = mysqli_query($conexion, $query);
    $avance = '';
    while ($row = $res->fetch_assoc()) {
        $avance = $row['detalleObs'];
    }
    return $avance;
}

function avancesotros($idTG)
{
    include('conexion.php');
    $query = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'OTROS'";
    $res = mysqli_query($conexion, $query);
    $avance = '';
    while ($row = $res->fetch_assoc()) {
        $avance = $row['detalleObs'];
    }
    return $avance;
}

function contarobsgenearl($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM observacion_ot WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contartemperatura($idTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM temperaturas_rondas WHERE idTG = " . $idTG;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarTablaRondas($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM parametros_ot_rondas WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarTemperaturaRondas($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM temperaturas_rondas WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}


function contarconsolaalarma($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarparametrosbms($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarRondasTG($idTG)
{
    include('conexion.php');

    $query = "SELECT COUNT(*) AS total FROM parametros_ot_rondas WHERE idTG = " . $idTG;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerParametrosRondas($idParametros, $nomCampo)
{
    include('conexion.php');

    $query = "SELECT " . $nomCampo . " AS campo FROM parametros_ot_rondas WHERE idParametros = " . $idParametros;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obtenervdrlbms($id)
{
    include('conexion.php');
    $query = "SELECT vdrl_bms AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obtenerifi($id)
{
    include('conexion.php');
    $query = "SELECT ifi AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obtenerifibms($id)
{
    include('conexion.php');
    $query = "SELECT ifi_bms AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obteneruci_adultobms($id)
{
    include('conexion.php');
    $query = "SELECT uci_adulto_bms AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obteneruci_adulto($id)
{
    include('conexion.php');
    $query = "SELECT uci_adulto AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obteneruti($id)
{
    include('conexion.php');
    $query = "SELECT uti_pediatrica AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obtenerutibms($id)
{
    include('conexion.php');
    $query = "SELECT uti_pediatrica_bms AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];
}

function obtenervdrl($id)
{
    include('conexion.php');
    $query = "SELECT vdrl AS campo FROM temperaturas_rondas WHERE idTemperatura = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['campo'];

}

function concatenaranalizadores($idTG)
{
    include('conexion.php');
    $sql = "SELECT analizadores_red FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['analizadores_red'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarfancoil($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['fan_coil'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarRiego($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['riego'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarSentina($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['sentina'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarVAV($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['vav'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarControladores($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['controladores'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarvigasfrias($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['vigas_frias'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}


function concatenarcw($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['cw'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarvinyvex($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['vin_vex'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenaruma($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM consola_alarma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';

    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['uma'] . '<br>';

    }
    return $datosConcatenados;
}


function concatenarpisosub($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['pisosuby2'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarpiso3($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['piso3'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarpisopabe($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['pabellones'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarpiso7($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['piso7'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarcrd($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['crd'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarurgencias($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['urgencias'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function concatenarhospitalizados($idTG)
{
    include('conexion.php');
    $sql = "SELECT * FROM parametros_uma_bms WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $datosConcatenados = '';
    $count = 1;
    while ($row = $res->fetch_assoc()) {
        $datosConcatenados .= $row['hospitalizados'] . '<br>';
        $count++;
    }
    return $datosConcatenados;
}

function obtenerPasswordJefeVisado($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT passJefe FROM jefes_tecnicos WHERE nomJefe LIKE '" . $nomUsuario . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['passJefe'];
}

function obstenerPasswordPersonalVisado($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT passPersonal FROM personal WHERE nomPersonal='" . $nomUsuario . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['passPersonal'];
}

function obtenerPasswordAdminVisado($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT passAdministrador FROM administracion WHERE nomAdministrador LIKE '" . $nomUsuario . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['passAdministrador'];
}

function obtenerPasswordAdminTecVisado($nomUsuario)
{
    include('conexion.php');
    $query = "SELECT passAdmTecnicos FROM adm_tecnicos WHERE nomAdmTecnicos = '" . $nomUsuario . "'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['passAdmTecnicos'];
}


function getVisadoHT($id)
{
    include('conexion.php');
    $query = 'SELECT nombreVisado FROM ordenes_trabajos_general WHERE idOrden = ' . $id . ' AND isVisado = 1';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['nombreVisado'];
}

function getRutaFirmaVisado($nom)
{
    include('conexion.php');
    $query = 'SELECT rutaVisado FROM visado_documentos WHERE nomVisado = "' . $nom . '"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaVisado'];
}

function getRutaFirmaVisadoYorgo()
{
    include('conexion.php');
    $query = 'SELECT rutaVisado FROM visado_documentos WHERE nomVisado = "Yorgo Casimis Subiabre"';
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['rutaVisado'];
}

function nocontrolavances($idTG)
{
    include('conexion.php');
    $query = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'NO-CONTROL'";
    $res = mysqli_query($conexion, $query);
    $avance = '';
    while ($row = $res->fetch_assoc()) {
        $avance = $row['detalleObs'];
    }
    return $avance;
}

function contarRespuestaNocontrol($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'NO-CONTROL'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarPermisosRevisadosJIJI($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM permisos_revisados WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarDetallesnoControl($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(detalleTG) AS TOTAL FROM trabajos_general WHERE idTG = $idTG AND detalleTG != ''";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarMaterial($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM linea_vida_ot WHERE idTG = $idTG AND isMaterialSoliCompra = 1";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarsoli($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM linea_vida_ot WHERE idTG = $idTG AND respuestaSoli = 1";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

// FIN FUNCION

function obtenerEstadoTG($idTG)
{
    include('conexion.php');
    $sql = "SELECT estadoTG FROM trabajos_general WHERE idTG = " . $idTG;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['estadoTG'];
}

function contarPanol($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM linea_vida_ot WHERE idTG = $idTG AND isPanol = 1";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarRechazadoPanol($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM linea_vida_ot WHERE idTG = $idTG AND rechazadoPanol = 1";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}

function contarAceptadoPanol($idTG)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM linea_vida_ot WHERE idTG = $idTG AND respuestaPanol = 1";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $TOTAL = $row['TOTAL'];
    }
    return $TOTAL;
}


function comprimirCarpeta($carpeta, $archivoZip)
{
    // Crear una instancia de ZipArchive
    $zip = new ZipArchive();

    // Intentar abrir el archivo ZIP para escritura
    if ($zip->open($archivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        // Obtener la lista de archivos en la carpeta
        $archivos = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($carpeta),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        // Agregar cada archivo al archivo ZIP
        foreach ($archivos as $archivo) {
            if (!$archivo->isDir()) {
                $archivoLocal = $archivo->getRealPath();
                $archivoRelativo = substr($archivoLocal, strlen($carpeta) + 1);

                $zip->addFile($archivoLocal, $archivoRelativo);
            }
        }

        // Cerrar el archivo ZIP
        $zip->close();

        return true; // Éxito
    } else {
        return false; // Fallo al abrir el archivo ZIP
    }
}

function obtenerCantidadArchivosHT($idOrden)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM archivos_orden_general WHERE idOrden = " . $idOrden;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function contarRecepcionDocu($idRecepcion)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM trabajos_general WHERE idRecepcionDouc = " . $idRecepcion;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function contarArchivosDocumento($idTG)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM archivos_recepcion_documento WHERE idTG = " . $idTG;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function contarVisadoHT($folioTG)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM ordenes_trabajos_general WHERE isVisado = 1 AND noOrdenTrabajo = '$folioTG'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function contarVisadoHTTodo($folioTG)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function contarVisadoOT($idTG)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM trabajos_general WHERE idTG = $idTG AND isVisado = 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function contarVisadoOTTodo($idTG)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function contarConsultaParaVisado($conexion, $query, $types, ...$params)
{
    $stmt = $conexion->prepare($query);
    if (!$stmt) return 0;

    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $stmt->bind_result($cantidad);
    $stmt->fetch();
    $stmt->close();

    return intval($cantidad);
}

function contarChecklistGeneral($idTG)
{
    include('conexion.php');
    $query = "SELECT count(*) as cantidad FROM tipos_check_list WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidad'];
}

function obtenerFechaTempPab()
{
    include('conexion.php');

    $query = "SELECT fechaTemperatura FROM pabellones_temperatura ORDER BY fechaTemperatura DESC LIMIT 1";
    $res = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);

        // Formatear la fecha al estilo 'yyyy-mm-dd H:i'
        $fechaFormateada = date('Y-m-d H:i', strtotime($dato['fechaTemperatura']));

        return $fechaFormateada;
    } else {
        // Manejar el caso en que no hay resultados
        return "Sin resultados";
    }
}

function obtenerTempPabxFecha($fecha, $numPab)
{
    include('conexion.php');

    $query = "SELECT pabellon" . $numPab . "Temperatura AS tempe FROM pabellones_temperatura WHERE fechaTemperatura LIKE '%" . $fecha . "%' LIMIT 1";
    $res = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);

        return $dato['tempe'];
    } else {
        // Manejar el caso en que no hay resultados
        return "Sin resultados";
    }
}

function obtenerHumedadPabxFecha($fecha, $numPab)
{
    include('conexion.php');

    $query = "SELECT pabellon" . $numPab . "Humedad AS humedadd FROM pabellones_humedades WHERE fechaHumedad LIKE '%" . $fecha . "%' LIMIT 1";
    $res = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);

        return $dato['humedadd'];
    } else {
        // Manejar el caso en que no hay resultados
        return "Sin resultados";
    }
}

function contarDetalleNoControl($idTG)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS CANTIDAD FROM observacion_ot WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['CANTIDAD'];

}

function buscarPatente1($otraPatente)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS TOTAL FROM patentes_movilizacion WHERE numPatente = '$otraPatente'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];
}

function contadortotalsolicCompra($anioSC)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS totalSolicitudes FROM  solicitudes_sc_sdo_resumen WHERE YEAR(fechaSoliCompra) = '$anioSC'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalSolicitudes'];

}

function contadoraprobadassda($anioSC)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS totalAprobadas FROM  solicitudes_compra_sdo WHERE (estadoSoliCompra = 'Autorizado por la S.D.A' || estadoSoliCompra =  'Derivado a Abastecimiento') AND YEAR(fechaSoliCompra) = '$anioSC'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalAprobadas'];
}

function contarrechazadassda($anioSC)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS totalRechazadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Rechazado por la S.D.A' AND YEAR(fechaSoliCompra) = '$anioSC' ";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalRechazadas'];
}

function contaranuladas($anioSC)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS totalAnuladas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Anulada' AND YEAR(fechaSoliCompra) = '$anioSC'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalAnuladas'];
}

function contarsolicitadassdo($anioSC)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS totalSecretaria FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'SOLICITADA A SECRETARIA SDO' AND YEAR(fechaSoliCompra) = '$anioSC'";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['totalSecretaria'];
}


function obtenerParametroPabCorreo($param, $id)
{
    include('conexion.php');
    $query = "SELECT " . $param . " AS param FROM pabellones_correo WHERE idRegistroPab = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['param'];
}

function obtenerFechaPabCorreo($id)
{
    include('conexion.php');
    $query = "SELECT fechaRegistroPab FROM pabellones_correo WHERE idRegistroPab = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['fechaRegistroPab'];
}


function getParamPabellonID($id)
{
    include('conexion.php');
    $query = "SELECT * FROM pabellones_correo WHERE idRegistroPab = " . $id;
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['idRegistroPab'] = $row['idRegistroPab'];
        $datos['nomRegistroPab'] = $row['nomRegistroPab'];
        $datos['fechaRegistroPab'] = $row['fechaRegistroPab'];
        $datos['ordenRegistroPab'] = $row['ordenRegistroPab'];
        for ($i = 2; $i <= 10; $i++) {
            $datos['tempPabellon' . $i] = $row['tempPabellon' . $i];
            $datos['humedadPabellon' . $i] = $row['humedadPabellon' . $i];
        }
        return $datos;
    } else {
        return 'null';
    }
}

function getDatosInventario($id)
{
    include('conexion.php');
    $query = "SELECT * FROM inventario_equipos WHERE idInventario = $id";
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['equipoInventario'] = $row['equipoInventario'];
        $datos['marcaInventario'] = $row['marcaInventario'];
        $datos['modeloInventario'] = $row['modeloInventario'];
        $datos['cantidadInventario'] = $row['cantidadInventario'];
        $datos['categoriaInventario'] = $row['categoriaInventario'];
        $datos['fechaInventario'] = $row['fechaInventario'];
        $datos['fechaActualizacion'] = $row['fechaActualizacion'];
        $datos['nomProveedor'] = $row['nomProveedor'];
        $datos['rutProveedor'] = $row['rutProveedor'];
        $datos['tipoDocumento'] = $row['tipoDocumento'];
        $datos['solicompra'] = $row['solicompra'];
        $datos['observacion'] = $row['observacion'];
        $datos['valorUnitario'] = $row['valorUnitario'];
        $datos['unidad'] = $row['unidad'];
        return $datos;
    } else {
        return 'null';
    }
}

function getnombrecategoria($id)
{
    include('conexion.php');
    $query = "SELECT nomCategoria FROM categorias_equipos WHERE idCategoria = $id";
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    mysqli_close($conexion);
    if ($row) {
        $datos = array();
        $datos['nomCategoria'] = $row['nomCategoria'];
        return $datos;
    } else {
        return 'null';
    }
}


function obtenerNomSalaUCA($id)
{
    include('conexion.php');

    $query = "SELECT nomSalaUCA FROM salas_ucas WHERE idSalaUCA = " . $id;
    $res = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);

        return $dato['nomSalaUCA'];
    } else {
        // Manejar el caso en que no hay resultados
        return "No Aplica";
    }
}

function obtenerUbicacionSalaUCA($id)
{
    include('conexion.php');

    $query = "SELECT ubicacionSalaUCA FROM salas_ucas WHERE idSalaUCA = " . $id;
    $res = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);

        return $dato['ubicacionSalaUCA'];
    } else {
        // Manejar el caso en que no hay resultados
        return "No Aplica";
    }
}

function obtenerPisoSalaUCA($id)
{
    include('conexion.php');

    $query = "SELECT pisoSalaUCA FROM salas_ucas WHERE idSalaUCA = " . $id;
    $res = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);

        return $dato['pisoSalaUCA'];
    } else {
        // Manejar el caso en que no hay resultados
        return "No Aplica";
    }
}

function contarPuertasUCA($id)
{
    include 'conexion.php';
    $sql = 'SELECT COUNT(*) AS total FROM ACS_DOOR_GROUP_DOOR WHERE deviceid = "' . $id . '" ORDER BY doorid ASC';
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function formatearRUTConK($rut)
{
    // Eliminar puntos y guión del RUT original
    $rutLimpio = str_replace(array('.', '-'), '', $rut);

    // Verificar si el dígito verificador es una "K" (mayúscula) o un número
    $digitoVerificador = substr($rutLimpio, -1);
    if ($digitoVerificador === 'K' || is_numeric($digitoVerificador)) {
        // Separar la parte numérica
        $parteNumerica = substr($rutLimpio, 0, -1);

        // Formatear la parte numérica con puntos y agregar el guión y el dígito verificador
        $rutFormateado = number_format($parteNumerica, 0, ',', '.') . '-' . $digitoVerificador;

        return $rutFormateado;
    } else {
        // Dígito verificador no válido
        return "Sín Información";
    }
}

function obtenerPresupuesto($id)
{
    include 'conexion.php';


    $sqlPresupuesto = "SELECT * FROM presupuesto_equipos WHERE idPresup = " . $id;

    $resPresupuesto = mysqli_query($conexion, $sqlPresupuesto);

    if ($resPresupuesto && mysqli_num_rows($resPresupuesto) > 0) {

        $row = $resPresupuesto->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idPresup'] = $row['idPresup'];
            $datos['folioPresup'] = $row['folioPresup'];
            $datos['creadorPresup'] = $row['creadorPresup'];
            $datos['fechaPresup'] = $row['fechaPresup'];
            $datos['unidadPresup'] = $row['unidadPresup'];
            $datos['valorPresup'] = $row['valorPresup'];
            $datos['descripcion'] = $row['descripcion'];
            return $datos;
        } else {
            return 'null';
        }
    } else {
        return 'null';
    }
}

function buscarchecklist($id)
{
    include 'conexion.php';
    $sql = "SELECT * FROM tipos_check_list WHERE idTG = $id";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['cantidad'] = $row['cantidad'];
            $datos['tipo'] = $row['tipo'];
            return $datos;
        } else {
            return 'null';
        }
    } else {
        return 'null';
    }
}

function buscarequipo($id)
{
    include 'conexion.php';
    $sql = "SELECT nombreEquipo FROM equipamiento_hbqp WHERE id = $id";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $nombreEquipo = $row['nombreEquipo'];
            return $nombreEquipo;
        } else {
            return $sql;
        }
    } else {
        return $sql;
    }
}


function contarPresupuestos($id)
{
    include 'conexion.php';


    $sqlPresupuesto = "SELECT COUNT(*) AS total FROM presupuesto_equipos WHERE idPresup = " . $id;

    $resPresupuesto = mysqli_query($conexion, $sqlPresupuesto);

    if ($resPresupuesto && mysqli_num_rows($resPresupuesto) > 0) {

        $dato = mysqli_fetch_assoc($resPresupuesto);
        return $dato['total'];
    } else {
        return 'null';
    }
}

function contarEquiposPresupuestos($id)
{
    include 'conexion.php';


    $sqlPresupuesto = "SELECT COUNT(*) AS total FROM equipos_presupuesto_equipos WHERE idPresup = " . $id;

    $resPresupuesto = mysqli_query($conexion, $sqlPresupuesto);

    if ($resPresupuesto && mysqli_num_rows($resPresupuesto) > 0) {

        $dato = mysqli_fetch_assoc($resPresupuesto);
        return $dato['total'];
    } else {
        return '0';
    }
}

function contarEquiposPresupuestosEquipos($id, $idEquipo)
{
    include 'conexion.php';


    $sqlPresupuesto = "SELECT COUNT(*) AS total FROM equipos_presupuesto_equipos WHERE idInventarioEquipo = $id AND idEquipo = $idEquipo";

    $resPresupuesto = mysqli_query($conexion, $sqlPresupuesto);

    if ($resPresupuesto && mysqli_num_rows($resPresupuesto) > 0) {

        $dato = mysqli_fetch_assoc($resPresupuesto);
        return $dato['total'];
    } else {
        return '0';
    }
}

function sumatotalequipos($id)
{
    include 'conexion.php';
    $sqlSumar = "SELECT SUM(valorTotalEquipo) AS total FROM equipos_presupuesto_equipos WHERE idPresup = $id";
    $resSumar = mysqli_query($conexion, $sqlSumar);
    while ($rowSumar = $resSumar->fetch_assoc()) {
        return $rowSumar['total'];
    }
}

function buscarRUTProveedor($rut)
{
    include 'conexion.php';


    $sql = "SELECT COUNT(*) AS total FROM proveedor_equipos WHERE rutProveedor = '" . $rut . "'";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $dato = mysqli_fetch_assoc($res);
        return $dato['total'];
    } else {
        return 0;
    }
}

function obtenerNombreProveedor($rut)
{
    include 'conexion.php';


    $sql = "SELECT nomProveedor FROM proveedor_equipos WHERE rutProveedor = '" . $rut . "'";;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $dato = mysqli_fetch_assoc($res);
        return $dato['nomProveedor'];
    } else {
        return 0;
    }
}

function obtenerRutProveedor($id)
{
    include 'conexion.php';


    $sql = "SELECT rutProveedor FROM proveedor_equipos WHERE idProveedor = " . $id;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $dato = mysqli_fetch_assoc($res);
        return $dato['rutProveedor'];
    } else {
        return 0;
    }
}

function obtenerNomProveedorID($id)
{
    include 'conexion.php';


    $sql = "SELECT nomProveedor FROM proveedor_equipos WHERE idProveedor = " . $id;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $dato = mysqli_fetch_assoc($res);
        return $dato['nomProveedor'];
    } else {
        return 0;
    }
}

function buscarequipopresu($id, $idPresupuesto)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS total FROM equipos_presupuesto_equipos WHERE idInventarioEquipo = $id AND idPresup = $idPresupuesto";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['total'];
        }
    }
    return $total;
}

function contarucassinconexion()
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM ucas_listado WHERE moduloIncendioUCA = 0 AND name NOT LIKE '%VTO%'";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['total'];
        }
    }
    return $total;
}

function contarucassinmodulo()
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS total FROM ucas_listado WHERE moduloIncendioUCA = -1";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['total'];
        }
    }
    return $total;
}

function contarucasconmodulo()
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS total FROM ucas_listado WHERE moduloIncendioUCA != 0 AND moduloIncendioUCA != -1 AND name NOT LIKE ('%VTO%')";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['total'];
        }
    }
    return $total;
}

function listadoInventarioDataList()
{
    include 'conexion.php';
    $sqlInventario = "SELECT DISTINCT equipoInventario, marcaInventario, modeloInventario, unidad, MAX(idInventario) AS idInventario, MAX(valorUnitario) AS valorUnitario FROM inventario_equipos GROUP BY equipoInventario ORDER BY equipoInventario ASC, fechaActualizacion DESC;";
    $resInventario = mysqli_query($conexion, $sqlInventario);

    $data = '';

    if ($resInventario && mysqli_num_rows($resInventario) > 0) {
        while ($rowInventario = mysqli_fetch_assoc($resInventario)) {
            $unidad = $rowInventario['unidad'];
            $valorUnitario = $rowInventario['valorUnitario'];
            $equipoInventario = $rowInventario['equipoInventario'];
            $idInventario = $rowInventario['idInventario'];
            $marcaInventario = $rowInventario['marcaInventario'];
            $modeloInventario = $rowInventario['modeloInventario'];

            $unidad = trim($unidad);
            $valorUnitario = trim($valorUnitario);
            $equipoInventario = trim($equipoInventario);
            $idInventario = trim($idInventario);
            $marcaInventario = trim($marcaInventario);
            $modeloInventario = trim($modeloInventario);

            if (empty($marcaInventario)) {
                $marcaInventario = 'S/I';
            } else {
                $marcaInventario = mb_convert_case($marcaInventario, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($modeloInventario)) {
                $modeloInventario = 'S/I';
            } else {
                $modeloInventario = mb_convert_case($modeloInventario, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($unidad)) {
                $unidad = 'S/I';
            } else {
                $unidad = mb_convert_case($unidad, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($valorUnitario)) {
                $valorUnitario = '0';
            } else {
                $valorUnitario = mb_convert_case($valorUnitario, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($equipoInventario)) {
                $equipoInventario = '';
            } else {
                $equipoInventario = mb_convert_case($equipoInventario, MB_CASE_UPPER, 'UTF-8');
            }

            $data .= '<option data-unidad="' . $unidad . '" data-idInvAgregar="' . $idInventario . '" data-valorSin="' . $valorUnitario . '" data-marca="' . $marcaInventario . '" data-modelo="' . $modeloInventario . '">' . $equipoInventario . '</option>';
        }
    } else {
        $data .= '';
    }

    return $data;
}


function listadoDataListInventarioHT()
{
    include 'conexion.php';
    $sqlInventario = "SELECT DISTINCT equipoInventario, marcaInventario, modeloInventario, unidad, idInventario, valorUnitario, serieInventario, cantidadInventario FROM inventario_equipos ORDER BY equipoInventario ASC, fechaActualizacion DESC;";
    $resInventario = mysqli_query($conexion, $sqlInventario);

    $data = '';

    if ($resInventario && mysqli_num_rows($resInventario) > 0) {
        while ($rowInventario = mysqli_fetch_assoc($resInventario)) {
            $unidad = $rowInventario['unidad'];
            $valorUnitario = $rowInventario['valorUnitario'];
            $equipoInventario = $rowInventario['equipoInventario'];
            $idInventario = $rowInventario['idInventario'];
            $marcaInventario = $rowInventario['marcaInventario'];
            $modeloInventario = $rowInventario['modeloInventario'];
            $serieInventario = $rowInventario['serieInventario'];
            $cantidadInventario = $rowInventario['cantidadInventario'];

            $unidad = trim($unidad);
            $valorUnitario = trim($valorUnitario);
            $equipoInventario = trim($equipoInventario);
            $idInventario = trim($idInventario);
            $marcaInventario = trim($marcaInventario);
            $modeloInventario = trim($modeloInventario);
            $serieInventario = trim($serieInventario);
            $cantidadInventario = trim($cantidadInventario);

            if (empty($marcaInventario)) {
                $marcaInventario = 'S/I';
            } else {
                $marcaInventario = mb_convert_case($marcaInventario, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($modeloInventario)) {
                $modeloInventario = 'S/I';
            } else {
                $modeloInventario = mb_convert_case($modeloInventario, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($unidad)) {
                $unidad = 'S/I';
            } else {
                $unidad = mb_convert_case($unidad, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($valorUnitario)) {
                $valorUnitario = '0';
            } else {
                $valorUnitario = mb_convert_case($valorUnitario, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($equipoInventario)) {
                $equipoInventario = '';
            } else {
                $equipoInventario = mb_convert_case($equipoInventario, MB_CASE_UPPER, 'UTF-8');
            }

            if (empty($serieInventario)) {
                $serieInventario2 = '';
            } else {
                $serieInventario2 = '(N° Serie: ' . $serieInventario . ')';
                $serieInventario2 = mb_convert_case($serieInventario2, MB_CASE_UPPER, 'UTF-8');
            }

            $data .= '<option data-unidad="' . $unidad . '" data-idInvAgregar="' . $idInventario . '" data-valorSin="' . $valorUnitario . '" data-marca="' . $marcaInventario . '" data-modelo="' . $modeloInventario . '" data-cantidad="' . $cantidadInventario . '">' . $equipoInventario . ' ' . $serieInventario2 . ' (' . $marcaInventario . ')</option>';
        }
    } else {
        $data .= '';
    }

    return $data;
}


function contarchecklistpantallasgraficas($id)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_checklist WHERE idTG = $id";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarchecklistlogicaprogram($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_checklist_logica WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarchecklistucas($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_checklist_ucas WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarchecklistcontroladores($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_checklist_controlador_ecb WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarchecklisttccuma($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_tcc_uma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarchecklistvav($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_vav WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarchecklistvf($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_vf WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarchecklistuma($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_uma WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarASTUCA($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM ast_participantes_otros WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;

}

function contarchecklistLLE($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM datos_checklist_lle WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}






function contartipocheck($idTG)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM tipos_check_list WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;

}

function contarucastotal()
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM ucas_listado WHERE name NOT LIKE '%VTO%'";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;
}

function contarPuertas()
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as TOTAL FROM ACS_DOOR_GROUP_DOOR AS acs JOIN ucas_listado AS uca ON acs.deviceid = uca.idUnique ORDER BY uca.name ASC, acs.doorid ASC;";
    $res = mysqli_query($conexion, $sql);
    $total = 0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['TOTAL'];
        }
    }
    return $total;

}

function calcularTiempoEquipoOperativo($nombre)
{
    include 'conexion.php';
    $sql = "SELECT * FROM historial_cambios_equipos_respaldo WHERE nombreEquipo LIKE '%$nombre%'";
    $res = mysqli_query($conexion, $sql);
    $datos = array();

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            // Cada fila de resultado se agrega como un elemento al array $datos
            $fila = array();
            $fila['fecha'] = $row['fecha'];
            $fila['estado'] = $row['estado'];
            $datos[] = $fila;
        }
        return $datos;
    } else {
        return 'null';
    }
}


function contarfaltavisado($nomUsuario)
{
    include 'conexion.php';
    $sql = "SELECT count(*) as total FROM trabajos_general WHERE solicitanteTG LIKE '%$nomUsuario%' AND (isVisado = 0 OR isVisado = null) AND estadoTG = 'Trabajo Terminado'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['total'];
    }
    return $total;
}

function buscarnombre($selectFuncionario)
{
    include 'conexion.php';
    $sql = "SELECT UserName FROM ACS_EventInfoTable WHERE JobNumber LIKE '%$selectFuncionario%'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = $row['UserName'];
    }
    return $datos;
}

function buscarNombreServicio($servicio)
{
    include 'conexion.php';
    $sql = "SELECT nomServicio FROM servicios WHERE idServicio = $servicio";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = $row['nomServicio'];
    }
    return $datos;

}

function buscararchivocoti($idSoliCompra)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM `archivo-orden-de-compra-panol` WHERE idSoliCompra = $idSoliCompra";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['TOTAL'];
    }
    return $total;
}

function buscararchivocoticc($idSoliCompra)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM `archivo-orden-de-compra-panol-cc` WHERE idSoliCompra = $idSoliCompra";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['TOTAL'];
    }
    return $total;
}

function contardigitalinput($nom)
{
    include "conexion.php"; // Se asegura de cargar la conexión una sola vez

    $sql = "SELECT COUNT(digitalInput_ControlBMS) as total 
            FROM detalle_control_bms 
            WHERE controlador_ControlBMS = ? AND digitalInput_ControlBMS != '-'";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nom);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    return $row['total'] ?? 0;
}

function contaranaloginput($nom)
{
    include "conexion.php";

    $sql = "SELECT COUNT(analogInput_ControlBMS) as total 
            FROM detalle_control_bms 
            WHERE controlador_ControlBMS = ? AND analogInput_ControlBMS != '-'";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nom);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    return $row['total'] ?? 0;
}

function contaranalogoutput($nom)
{
    include "conexion.php";

    $sql = "SELECT COUNT(analogOutput_ControlBMS) as total 
            FROM detalle_control_bms 
            WHERE controlador_ControlBMS = ? 
            AND analogOutput_ControlBMS != '-' 
            AND analogOutput_ControlBMS IS NOT NULL";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nom);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    return $row['total'] ?? 0;
}

function contardigitaloutput($nom)
{
    include "conexion.php";

    $sql = "SELECT COUNT(digitalOutput_ControlBMS) as total 
            FROM detalle_control_bms 
            WHERE controlador_ControlBMS = ? AND digitalOutput_ControlBMS != '-'";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nom);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    return $row['total'] ?? 0;
}

function buscarequipoPadre($id)
{
    if (empty($id) || $id == 0) return 'null';

    include "conexion.php";

    $sql = "SELECT nombreEquipo FROM equipamiento_hbqp WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        return $row['nombreEquipo'];
    }

    return 'null';
}

function contarCantidadAccesos($rut)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(JOBNumber) as total, MAX(EventTime) as ultimo FROM ACS_EventInfoTable WHERE JobNumber = '$rut'";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            if ($row['total'] > 0) {
                return $row['ultimo'];
            } else {
                return 'Sin Registros';
            }
        } else {
            return 'Sin Registros';
        }
    } else {
        return 'Sin Registros';
    }
}

function buscararchivostecnicos($id)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS total FROM Archivos_Trabajo_General_Tecnico WHERE idTG = $id";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total = $row['total'];
        }
        return $total;
    } else {
        return 0;
    }
}


function fechasPlanificaciones($anio)
{
    include 'conexion.php';
    $sql = "SELECT MIN(inicioPlanTareaP) AS minimo, MAX(finPlanTareaP) AS maximo FROM tareas_carta_gantt WHERE YEAR(inicioPlanTareaP) = '$anio'";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {

            if (empty($row['minimo'])) {
                return 'Sin Información';
            } else {
                if (empty($row['maximo'])) {
                    return 'Sin Información';
                } else {
                    $fechaMinimo = strtotime($row['minimo']);
                    $fechaMaximo = strtotime($row['maximo']);

                    $fechaMinimo2 = fechaEspañol3($fechaMinimo);
                    $fechaMaximo2 = fechaEspañol3($fechaMaximo);
                    return $fechaMinimo2 . ' a ' . $fechaMaximo2;
                }
            }

        } else {
            return 'Sin Información';
        }
    } else {
        return 'Sin Información';
    }
}

function contarPlanificacionesTotal($anio)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM tareas_carta_gantt WHERE YEAR(inicioPlanTareaP) = '$anio'";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            if ($row['total'] > 0) {
                return $row['total'] . ' Tareas';
            } else {
                return 0 . ' Tareas';
            }
        } else {
            return 0 . ' Tareas';
        }
    } else {
        return 0 . ' Tareas';
    }
}

function contarPlanificacionesRealizadas($anio)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM tareas_carta_gantt WHERE finalizadaTareaP = 1 AND YEAR(inicioPlanTareaP) = '$anio'";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            if ($row['total'] > 0) {
                return $row['total'] . ' Tareas';
            } else {
                return 0 . ' Tareas';
            }
        } else {
            return 0 . ' Tareas';
        }
    } else {
        return 0 . ' Tareas';
    }
}

function contarPlanificacionesEnDesarrollo($anio)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM tareas_carta_gantt WHERE (finalizadaTareaP = 0 AND iniciadaTareaP = 1) AND YEAR(inicioPlanTareaP) = '$anio'";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            if ($row['total'] > 0) {
                return $row['total'] . ' Tareas';
            } else {
                return 0 . ' Tareas';
            }
        } else {
            return 0 . ' Tareas';
        }
    } else {
        return 0 . ' Tareas';
    }
}

function contarPlanificacionesSinIniciar($anio)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM tareas_carta_gantt WHERE iniciadaTareaP = 0 AND YEAR(inicioPlanTareaP) = '$anio'";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            if ($row['total'] > 0) {
                return $row['total'] . ' Tareas';
            } else {
                return 0 . ' Tareas';
            }
        } else {
            return 0 . ' Tareas';
        }
    } else {
        return 0 . ' Tareas';
    }
}

function obtenerDashboardSC($sql)
{
    include 'conexion.php';
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            if ($row['total'] > 0) {
                return $row['total'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function calcularPorcentajesSC($valorMultip1, $valorMultip2, $valorDivisor)
{
    $multiplicacion = $valorMultip1 * $valorMultip2;
    if ($multiplicacion > 0) {
        $resto = $multiplicacion / $valorDivisor;
        if ($resto > 0) {
            $valorFinal = $resto;
        } else {
            $valorFinal = 0;
        }
    } else {
        $valorFinal = 0;
    }
    $valorFinal2 = round($valorFinal, 1);
    return $valorFinal2;
}


function generarCupoArchivoCotizacionInvent()
{
    include 'conexion.php';
    $sql = "INSERT INTO archivos_cotizacion_inventario(idArchivo,nomArchivo,rutaArchivo,extArchivo,tipoCotizacion,idInventario) VALUES (NULL,'Cargando','Cargando','Cargando','Cargando',0)";
    $res = mysqli_query($conexion, $sql);
    $valor = '';
    if ($res) {
        // Obtener el ID del último registro insertado
        $idInsertado = mysqli_insert_id($conexion);
        $valor = $idInsertado;
    } else {
        $valor = 'error';
    }

    return $valor;
}

function contarModelosDistintos($modelo, $idCarpeta)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM equipamiento_hbqp WHERE modelo LIKE '" . $modelo . "' AND idCategoria = " . $idCarpeta;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerMayorValorEquipamiento($modelo, $idCarpeta)
{
    include('conexion.php');
    $query = "SELECT MAX(valorConIva) AS valorMax FROM equipamiento_hbqp WHERE modelo LIKE '" . $modelo . "' AND idCategoria = " . $idCarpeta;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['valorMax'];
}

function obtenerMayorValorEquipamientoTotal($modelo, $idDepartamento1)
{
    include('conexion.php');
    $query = "SELECT MAX(valorConIva) AS valorMax FROM equipamiento_hbqp WHERE modelo LIKE '" . $modelo . "' AND idDepaPertenece = " . $idDepartamento1;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $query;
}

function obtenerValorSistemaEquipo($idCarpeta)
{
    include('conexion.php');
    $query = "SELECT SUM(valorConIva) AS total FROM equipamiento_hbqp WHERE idCategoria = " . $idCarpeta;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $total = $dato['total'];

    if ($total > 0) {
        $total2 = $total;
    } else {
        $total2 = 0;
    }

    return $total2;
}


function obtenerIDEquipamientoPadre($id)
{
    include('conexion.php');

    $query = "SELECT idEquipoPadre FROM equipamiento_hbqp WHERE id = " . $id;
    $res = mysqli_query($conexion, $query);

    // Verificar si se obtuvieron resultados
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);

        return $dato['idEquipoPadre'];
    } else {
        // Manejar el caso en que no hay resultados
        return "No Aplica.";
    }
}


function getRutaImagenEquipamiento($modelo, $marca)
{
    include('conexion.php');

    $modelo = trim($modelo ?? '');
    $marca  = trim($marca ?? '');

    if ($marca === 'NO APLICA') {
        $marca = '';
    }

    $modelo = mysqli_real_escape_string($conexion, $modelo);
    $marca  = mysqli_real_escape_string($conexion, $marca);

    $query = "SELECT rutaImagen
              FROM equipamiento_hbqp
              WHERE modelo LIKE '%{$modelo}%'
                AND marca LIKE '%{$marca}%'
                AND rutaImagen IS NOT NULL
                AND TRIM(rutaImagen) <> ''
              LIMIT 1";

    $res = mysqli_query($conexion, $query);

    if (!$res) {
        return '';
    }

    $dato = mysqli_fetch_assoc($res);

    return $dato['rutaImagen'] ?? '';
}

function getValorEquipamiento($modelo, $marca)
{
    include('conexion.php');
    $query = "SELECT valorConIva  FROM equipamiento_hbqp WHERE (modelo LIKE '%" . $modelo . "%' AND marca LIKE '%" . $marca . "%') ORDER BY valorConIva DESC LIMIT 1";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['valorConIva'];
//    return $query;
}

function contarequiposcategoria($idCategoria)
{
    include('conexion.php');
    $cuentaEquiposRegistrados = "SELECT COUNT(*) AS cantidadEquiposRegistrados FROM equipamiento_hbqp WHERE idCategoria = $idCategoria AND idEquipoPadre > -1 ";
    $res = mysqli_query($conexion, $cuentaEquiposRegistrados);
    while ($row = $res->fetch_assoc()) {
        $cantidadEquiposRegistrados = $row['cantidadEquiposRegistrados'];
    }
    return $cantidadEquiposRegistrados;
}

function contareuiposoperativos($idCategoria)
{
    include('conexion.php');
    $cuentaEquiposOperativos = "SELECT COUNT(*) AS cuentaEquiposOperativos FROM equipamiento_hbqp WHERE idCategoria = $idCategoria && estadoEquipo LIKE 'Operativo' AND idEquipoPadre > -1 ";
    $res = mysqli_query($conexion, $cuentaEquiposOperativos);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaEquiposOperativos'] = $row['cuentaEquiposOperativos'];
        $cuentaEquiposOperativos = $datos['cuentaEquiposOperativos'];
    }
    return $cuentaEquiposOperativos;
}

function contarequiposobs($idCategoria)
{
    include('conexion.php');
    $sqlCuentaEquiposConObservaciones = "SELECT COUNT(*) AS cuentaEquiposConObservaciones FROM equipamiento_hbqp WHERE idCategoria = $idCategoria && estadoEquipo LIKE 'OperativoObs' AND idEquipoPadre > -1";
    $res = mysqli_query($conexion, $sqlCuentaEquiposConObservaciones);
    while ($row = $res->fetch_assoc()) {
        $cuentaEquiposConObservaciones = $row['cuentaEquiposConObservaciones'];
    }
    return $cuentaEquiposConObservaciones;

}

function contarequiposmanteni($idCategoria)
{
    include('conexion.php');
    $cantEquiposMantenimiento = "SELECT COUNT(*) AS cantEquiposMantenimiento FROM equipamiento_hbqp WHERE idCategoria = $idCategoria && estadoEquipo LIKE '%Mantenimiento%' AND idEquipoPadre > -1";
    $res = mysqli_query($conexion, $cantEquiposMantenimiento);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposMantenimiento'] = $row['cantEquiposMantenimiento'];
        $cantEquiposMantenimiento = $datos['cantEquiposMantenimiento'];
    }
    return $cantEquiposMantenimiento;
}

function contarequiposfallas($idCategoria)
{
    include('conexion.php');
    $cantEquiposFalla = "SELECT COUNT(*) AS cantEquiposFalla FROM equipamiento_hbqp WHERE idCategoria = $idCategoria AND estadoEquipo LIKE 'Fuera de Servicio' AND idEquipoPadre > -1 ";
    $res = mysqli_query($conexion, $cantEquiposFalla);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposFalla'] = $row['cantEquiposFalla'];
        $cantEquiposFalla = $datos['cantEquiposFalla'];
    }
    return $cantEquiposFalla;
}


function contarequiposextraviados($idCategoria)
{
    include('conexion.php');
    $sqlcuentaEquiposExtraviados = "SELECT COUNT(*) AS cuentaEquiposExtraviados FROM equipamiento_hbqp WHERE idCategoria = $idCategoria && estadoEquipo LIKE 'Extraviado' AND idEquipoPadre > -1 ";
    $res = mysqli_query($conexion, $sqlcuentaEquiposExtraviados);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaEquiposExtraviados'] = $row['cuentaEquiposExtraviados'];
        $cuentaEquiposExtraviados = $datos['cuentaEquiposExtraviados'];
    }
    return $cuentaEquiposExtraviados;
}

function contarvalorsistematotal($idCategoria)
{
    include('conexion.php');
    $sqlContarValor = "SELECT SUM(valorConIva) as Total FROM equipamiento_hbqp WHERE idCategoria = $idCategoria";
    $resContarValor = mysqli_query($conexion, $sqlContarValor);
    while ($rowContar = $resContarValor->fetch_assoc()) {
        $Total = $rowContar['Total'];
    }
    return $Total;
}

function contarTicketSIGHaniomes($anio, $i)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i' AND nomTecnicoTicketSIGH NOT LIKE ('%Pamela Vicencio Carvacho%') AND nomTecnicoTicketSIGH NOT LIKE ('%Estefano Villaseca%') AND nomTecnicoTicketSIGH NOT LIKE ('%Vicente Donoso%')";
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $frecuencia = $row['frecuencia'];
    }
    return $frecuencia;
}


function obtenerNumeroMemoSDO()
{
    include('conexion.php');
    $query = "SELECT MAX(idMemo) AS ultNumMemo FROM memorandum_admin;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $ultNumMemo = $dato['ultNumMemo'];
    if ($ultNumMemo > 0) {
        $datoMandar = $ultNumMemo + 1;
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}

function obtenerNumeroMemoIngOperativa($anio)
{
    include('conexion.php');
    $query = "SELECT MAX(numeroMemo) AS ultNumMemo FROM memorandum_sdo WHERE YEAR(fechaCreacionMemo) = $anio;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $ultNumMemo = (int)$dato['ultNumMemo'];
    if ($ultNumMemo > 0) {
        $datoMandar = $ultNumMemo + 1;
    } else {
        $datoMandar = 1;
    }

    return $datoMandar;
}


function obtenerNumeroMemoEspecialidades($id)
{
    include('conexion.php');
    $query = "SELECT MAX(idMemo) AS ultNumMemo FROM memorandum_especialidades WHERE idDepartamentoMemo = $id;";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $ultNumMemo = $dato['ultNumMemo'];
    if ($ultNumMemo > 0) {
        $datoMandar = $ultNumMemo + 1;
    } else {
        $datoMandar = 1;
    }

    return $datoMandar;
}

function capitalizarCargosMemo($cadena)
{
    // Dividir la cadena en palabras
    $palabras = explode(' ', $cadena);

// Palabras que no deben ser capitalizadas
    $excepciones = array('de', 'la');

// Capitalizar todas las palabras excepto las excepciones
    foreach ($palabras as &$palabra) {
        // Convertir la palabra a minúsculas (excepto si es una excepción)
        $palabra = mb_convert_case($palabra, MB_CASE_LOWER, 'UTF-8');
        if (!in_array(strtolower($palabra), $excepciones)) {
            $palabra = mb_convert_case($palabra, MB_CASE_TITLE, 'UTF-8');
        }
    }

// Unir las palabras nuevamente en una cadena
    $nuevaCadena = implode(' ', $palabras);

    return $nuevaCadena;
}


function buscarNombDes($nomDestinatario)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM destinatario_memo_admin WHERE nomDesti LIKE ('%$nomDestinatario%')";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['TOTAL'];
    }
    return $total;

}

function buscarCargo($cargoDestinatario)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM destinatario_memo_admin WHERE cargoDesti LIKE ('%$cargoDestinatario%')";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['TOTAL'];
    }
    return $total;
}

function buscarDestinatarioEspecialidades($nomDestinatario, $cargoDestinatario, $idDepartamento)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) AS TOTAL FROM destinatario_memo_especialidades WHERE (nomDesti LIKE ('%$nomDestinatario%')) AND (cargoDesti LIKE ('%$cargoDestinatario')) AND (idDepartamento LIKE ('%$idDepartamento%')) ";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['TOTAL'];
    }
    return $total;

}

function pasarFormatoDateInterval($minutos)
{

    // Calcular años, meses, días, horas y minutos
    $anios = floor($minutos / (365 * 24 * 60));
    $meses = floor(($minutos % (365 * 24 * 60)) / (30 * 24 * 60));
    $dias = floor(($minutos % (30 * 24 * 60)) / (24 * 60));
    $horas = floor(($minutos % (24 * 60)) / 60);
    $minutos = $minutos % 60;

// Mostrar el resultado
    $data = "$anios años, $meses meses, $dias días, $horas horas, $minutos minutos.";

    return $data;
}

function obtenerCantidadHistorialEquipo($idEquipo)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) AS TOTAL FROM historial_cambios_equipos WHERE idEquipo = " . $idEquipo;
    $res = mysqli_query($conexion, $sql);
    $dato = mysqli_fetch_assoc($res);
    return $dato['TOTAL'];

}

function generarTotalSQL($query)
{
    include('conexion.php');
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['cantidadTotal'];
}

function buscarDepartamentoPorRut($rut)
{

    include('conexion.php');

    $query = "SELECT departamento FROM ( SELECT nomJefe AS nombre, rutJefes AS rut, idDepartamento AS departamento FROM jefes_tecnicos UNION SELECT nomPersonal AS nombre, rutPersonal AS rut, departamentoPersonal AS departamento FROM personal ) AS combined_results WHERE rut = '" . $rut . "';";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $departamento = $dato['departamento'];
    if ($departamento > 0) {
        $nomDepartamento = getNomDepartamento($departamento);
        $datoMandar = $nomDepartamento;
    } else {
        $datoMandar = 'Nulo';
    }

    return $datoMandar;
}

function buscarDeptoSIGH($id)
{

    include('conexion.php');

    $query = "SELECT nomDepto FROM departamentos_sigh WHERE idDepto = " . $id;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    $departamento = $dato['nomDepto'];
    if (!empty($departamento)) {
        $datoMandar = $departamento;
    } else {
        $datoMandar = 'No Aplica';
    }

    return $datoMandar;
}

function buscarTipoProblemaSIGH($id)
{

    include('conexion.php');

    $query = "SELECT nomTipoSolicitud FROM solicitudes_tipos WHERE idSIGH = " . $id;
    $res = mysqli_query($conexion, $query);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['nomTipoSolicitud'])) {
            $nomTipoSolicitud = $dato['nomTipoSolicitud'];
            if (!empty($nomTipoSolicitud)) {
                $datoMandar = $nomTipoSolicitud;
            } else {
                $datoMandar = 'No Aplica';
            }
        } else {
            $datoMandar = 'No Aplica';
        }
    } else {
        $datoMandar = 'No Aplica';
    }

    return $datoMandar;
}

function cambiarFechaSlashADateTime($fecha)
{

    $componentes = sscanf($fecha, "%d/%d/%d %d:%d:%d");
    $datoMandar = '';

    if ($componentes !== false) {
        // Crear un objeto DateTime con los componentes
        $fecha = new DateTime();
        $fecha->setDate($componentes[2], $componentes[1], $componentes[0]); // Año, mes, día
        $fecha->setTime($componentes[3], $componentes[4], $componentes[5]); // Hora, minuto, segundo

        $datoMandar = $fecha->format('Y-m-d H:i:s');
    } else {
        $datoMandar = 'No Aplica';
    }

    return $datoMandar;
}

function buscarpma($puerta, $idCheck)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) as total FROM puertas_pma_checklist WHERE puerta = '$puerta' AND idCheck = $idCheck";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['total'];
    }
    return $total;
}


function contarHTParaInforme($idDepartamento, $fechaT)
{
    include('conexion.php');

    $fechaInicio = $fechaT . '-01 00:00:00';
    $fechaTermino = $fechaT . '-31 23:59:59';

    // Preparar consulta
    $query = "
        SELECT COUNT(*) as total
        FROM ordenes_trabajos_general
        WHERE 
            " . ($idDepartamento == 1 ? "(idDepartamento = 1 OR idDepartamento = 13)" : "idDepartamento = $idDepartamento") . "
            AND fechaOrden BETWEEN '$fechaInicio' AND '$fechaTermino'
    ";

    $res = mysqli_query($conexion, $query);
    if (!$res) {
        return 0;
    }

    $dato = mysqli_fetch_assoc($res);
    return $dato['total'] ?? 0;
}


function contarHTPorPersonaParaInforme($idDepartamento, $nomEmpleado, $fechaT)
{
    $fechaInicio = $fechaT . '-01 00:00:00';
    $fechaTermino = $fechaT . '-31 23:59:59';

    include('conexion.php');


    if ($idDepartamento == 1) {
        $query = "SELECT COUNT(*) AS total FROM ordenes_trabajos_general WHERE (idDepartamento = 1 OR idDepartamento = 13) AND (fechaOrden BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "') AND (nomTecnicoRecepcionador LIKE '" . $nomEmpleado . "');";
    } else {
        $query = "SELECT COUNT(*) as total FROM ordenes_trabajos_general WHERE (idDepartamento = $idDepartamento) AND (fechaOrden BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "') AND (nomTecnicoRecepcionador LIKE '" . $nomEmpleado . "')";
    }

    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total']; // Formateamos el total con comas
}

function buscarnombreunidad($idUnidad)
{
    include('conexion.php');
    $sql = "SELECT unidadFormCCAA FROM formularios_acceso WHERE idFormCCAA = $idUnidad";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $unidad = $row['unidadFormCCAA'];
    }
    return $unidad;
}

function buscarIdOrden($fechaRonda)
{
    include('conexion.php');
    $sqlFecha = "SELECT idOrden FROM ordenes_trabajos_general WHERE fechaOrden = '$fechaRonda'";
    $resFecha = mysqli_query($conexion, $sqlFecha);
    while ($rowFecha = $resFecha->fetch_assoc()) {
        $idOrden = $rowFecha['idOrden'];
    }
    return $idOrden;
}


function obtenerTanqueUtilizado($fecha)
{
    include 'conexion.php';
    $sql = "SELECT tanqueUtilizado FROM combustible_medicion WHERE fechaMC LIKE '%$fecha%' ORDER BY fechaMC DESC LIMIT 1 ";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            if (!empty($row['tanqueUtilizado'])) {
                if ($row['tanqueUtilizado'] == 'TanqueN°1') {
                    return 'Tanque N°1';
                } elseif ($row['tanqueUtilizado'] == 'TanqueN°2') {
                    return 'Tanque N°2';
                } elseif ($row['tanqueUtilizado'] == 'TanqueN°3') {
                    return 'Tanque N°3';
                } elseif ($row['tanqueUtilizado'] == 'TanqueN°4') {
                    return 'Tanque N°4';
                } else {
                    return 'No Aplica';
                }
            } else {
                return 'No Aplica';
            }
        } else {
            return 'No Aplica';
        }
    } else {
        return 'No Aplica';
    }
}

// Función para comprobar si la URL existe
function urlExists($url)
{
    $headers = @get_headers($url);
    if ($headers && strpos($headers[0], '200')) {
        return true;
    } else {
        return false;
    }
}


function obtenerDatosTanque()
{
    include 'conexion.php';


    $sqlTanque = "SELECT * FROM tanque_indura ORDER BY fechaActivacion DESC LIMIT 1";

    $resTanque = mysqli_query($conexion, $sqlTanque);

    if ($resTanque && mysqli_num_rows($resTanque) > 0) {

        $row = $resTanque->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idTanque'] = $row['idTanque'];
            $datos['codigoClienteTanque'] = $row['codigoClienteTanque'];
            $datos['nomClienteTanque'] = $row['nomClienteTanque'];
            $datos['direccionTanque'] = $row['direccionTanque'];
            $datos['tipoProductoTanque'] = $row['tipoProductoTanque'];
            $datos['numSerieTanque'] = $row['numSerieTanque'];
            $datos['capacidadTanqueIN'] = $row['capacidadTanqueIN'];
            $datos['capacidadTanqueM3'] = $row['capacidadTanqueM3'];
            $datos['capacidadTanqueGAL'] = $row['capacidadTanqueGAL'];
            $datos['fechaActivacion'] = $row['fechaActivacion'];
            return $datos;
        } else {
            return 'null';
        }
    } else {
        return 'null';
    }
}


function obtenerUltimoRegistroTanqueIndura()
{
    include 'conexion.php';


    $sqlTanque = "SELECT * FROM medicion_tanque_indura ORDER BY fechaMedicion DESC LIMIT 1";

    $resTanque = mysqli_query($conexion, $sqlTanque);

    if ($resTanque && mysqli_num_rows($resTanque) > 0) {

        $row = $resTanque->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idMedicion'] = $row['idMedicion'];
            $datos['lecturaMedicion'] = $row['lecturaMedicion'];
            $datos['unidadMedicion'] = $row['unidadMedicion'];
            $datos['fechaMedicion'] = $row['fechaMedicion'];
            $datos['fuenteMedicion'] = $row['fuenteMedicion'];
            $datos['tanqueMedicion'] = $row['tanqueMedicion'];
            $datos['fechaSubidaMedicion'] = $row['fechaSubidaMedicion'];
            return $datos;
        } else {
            return 'null';
        }
    } else {
        return 'null';
    }
}

function obtenerIDTanqueIndura()
{
    include 'conexion.php';


    $sqlTanque = "SELECT idTanque FROM tanque_indura ORDER BY fechaActivacion DESC LIMIT 1";

    $resTanque = mysqli_query($conexion, $sqlTanque);

    if ($resTanque && mysqli_num_rows($resTanque) > 0) {

        while ($rowTanque = $resTanque->fetch_assoc()) {
            $idTanque = $rowTanque['idTanque'];
        }
        return $idTanque;

    } else {
        return '0';
    }
}

function excelToDate($excelDate)
{
    // Convertir el valor de Excel a un timestamp Unix
    $unixDate = ($excelDate - 25569) * 86400;

    // Crear un objeto DateTime con el timestamp Unix
    $dateTime = new DateTime("@$unixDate");

    // Sumar un segundo
    $dateTime->modify('+1 second');

    // Formatear la nueva fecha y hora en el formato deseado
    return $dateTime->format("Y-m-d H:i");
}

function excelToDateSegundo($excelDate)
{
    // Convertir el valor de Excel a un timestamp Unix
    $unixDate = ($excelDate - 25569) * 86400;

    // Crear un objeto DateTime con el timestamp Unix
    $dateTime = new DateTime("@$unixDate");

    // Sumar un segundo
    $dateTime->modify('+1 second');

    // Formatear la nueva fecha y hora en el formato deseado
    return $dateTime->format("Y-m-d H:i:s");
}

function intOdecimal($numero)
{
    if (intval($numero) == $numero) {
        // Si no tiene decimales, mostrar como entero
        $numeroFormateado = number_format($numero, 0, ',', '.');
    } else {
        // Si tiene decimales, mostrar con dos decimales
        $numeroFormateado = number_format($numero, 2, ',', '.');
    }
    return $numeroFormateado;
}


function verificarExistenciaRegTanque($lecturaMedicion, $unidadMedicion, $fechaMedicion, $fuenteMedicion, $tanqueMedicion)
{
    include 'conexion.php';

    $sql_verificar = "SELECT COUNT(*) AS conteo FROM medicion_tanque_indura 
                  WHERE lecturaMedicion = '$lecturaMedicion' 
                  AND unidadMedicion = '$unidadMedicion' 
                  AND fechaMedicion = '$fechaMedicion' 
                  AND fuenteMedicion = '$fuenteMedicion' 
                  AND tanqueMedicion = '$tanqueMedicion'";

    $resTanque = mysqli_query($conexion, $sql_verificar);

    if ($resTanque && mysqli_num_rows($resTanque) > 0) {

        while ($rowTanque = $resTanque->fetch_assoc()) {
            $conteo = $rowTanque['conteo'];
        }
        return $conteo;

    } else {
        return '0';
    }
}

function verificarExistenciaRegAlarmaEquipamiento($fechaAlarma, $stateAlarma, $sourceAlarma, $classAlarma, $mensajeAlarma, $notasAlarma)
{
    include 'conexion.php';

    $sql_verificar = "SELECT COUNT(*) AS conteo FROM bms_alarmas_equipamiento 
                  WHERE fechaAlarma = '$fechaAlarma' 
                  AND stateAlarma = '$stateAlarma' 
                  AND sourceAlarma = '$sourceAlarma' 
                  AND classAlarma = '$classAlarma' 
                  AND classAlarma = '$classAlarma' 
                  AND notasAlarma = '$notasAlarma'";

    $res_verificar = mysqli_query($conexion, $sql_verificar);

    if ($res_verificar && mysqli_num_rows($res_verificar) > 0) {

        while ($row_verificar = $res_verificar->fetch_assoc()) {
            $conteo = $row_verificar['conteo'];
        }
        return $conteo;

    } else {
        return '0';
    }
}

function contarExisteDocumento($idFormRegAcceso)
{
    include 'conexion.php';
    $sql = "SELECT COUNT(*) as total FROM formularios_documentacion WHERE idFormRegAcceso = $idFormRegAcceso";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $total = $row['total'];
    }
    return $total;
}

function getChileNews($apiKey)
{
    $url = "http://api.mediastack.com/v1/news?access_key=$apiKey&countries=cl&languages=es";
    $response = file_get_contents($url);
    if ($response === false) {
        die("Error: No se pudo conectar a Mediastack.");
    }

    $newsData = json_decode($response, true);
    return $newsData['data'];
}

function buscarcargopersonal($nombre)
{
    include 'conexion.php';
    $sql = "SELECT * FROM personal WHERE nomPersonal LIKE('%$nombre%')";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['cargoPersonal'] = $row['cargoPersonal'];
            return $datos;
        } else {
            return 'nada1';
        }
    } else {
        return 'nada2';
    }
}


function contarSolicitudesSCSDO($anio)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = " . $anio;
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function contarVisaciones($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) as total FROM visado_registro_comunicacion WHERE idRegistro = $id";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}

function obtenerTipoMenu($id)
{
    include 'conexion.php';
    $sql = "SELECT tipoMenu FROM equipamiento_hbqp WHERE id = $id";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {

        $dato = mysqli_fetch_assoc($res);
        mysqli_close($conexion);
        $tipoMenu = $dato['tipoMenu'];
    } else {
        $tipoMenu = '';
    }
    return $tipoMenu;
}

function buscarMateriales($idOrden)
{
    include 'conexion.php';
    $sql = "SELECT count(*) as total FROM material_orden_general WHERE idOrden = $idOrden";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);
        mysqli_close($conexion);
        $total = $dato['total'];
    } else {
        $total = 0;
    }
    return $total;
}


function obtenerRutaDocumentacionEquipamiento($id)
{
    include('conexion.php');
    $query = "SELECT rutaArchivo FROM archivos_equipamiento_hbqp WHERE id = $id";
    $res = mysqli_query($conexion, $query);
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);
        mysqli_close($conexion);
        $rutaArchivo = $dato['rutaArchivo'];
    } else {
        $rutaArchivo = '';
    }
    return $rutaArchivo;
}

function obtenerRutaHallazgoEquipamiento($id)
{
    include('conexion.php');
    $query = "SELECT rutaArchivo FROM archivos_equipamiento_hbqp_hallazgos WHERE id = $id";
    $res = mysqli_query($conexion, $query);
    if ($res && mysqli_num_rows($res) > 0) {
        $dato = mysqli_fetch_assoc($res);
        mysqli_close($conexion);
        $rutaArchivo = $dato['rutaArchivo'];
    } else {
        $rutaArchivo = '';
    }
    return $rutaArchivo;
}


function buscarIDDeptoPrevencion()
{
    include 'conexion.php';
    $sql = "SELECT DEPT_ID FROM Department_table WHERE DEPT_FULL_NAME = 'HIGIENE Y SEGURIDAD\\PREVENCION'";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);
//        $deptoID = 0;
        if ($row) {
            $deptoID = $row['DEPT_ID'];
        } else {
            $deptoID = 0;
        }
    } else {
        $deptoID = 0;
    }

    return $deptoID;
}


function buscarNombrePuertaACS($DoorUniqueID)
{
    include 'conexion.php';
    $sql = "SELECT name FROM acs_door WHERE idUnique = $DoorUniqueID";
    $res = mysqli_query($conexion, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);
//        $name = 0;
        if ($row) {
            $name = $row['name'];
        } else {
            $name = '';
        }
    } else {
        $name = '';
    }

    return $name;
}


function obtenerDocumentoSolicitud($idDocumento)
{
    include 'conexion.php';

    $sql = "SELECT * FROM documentos_generados WHERE idDoc = " . $idDocumento;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idDoc'] = $row['idDoc'];
            $datos['tituloDoc'] = $row['tituloDoc'];
            $datos['textoDoc'] = $row['textoDoc'];
            $datos['fechaDoc'] = $row['fechaDoc'];
            $datos['creadorDoc'] = $row['creadorDoc'];
            $datos['rolCreadorDoc'] = $row['rolCreadorDoc'];
            return $datos;
        } else {
            return 'vacio';
        }
    } else {
        return 'vacio';
    }
}

function obtenerStatusCredencialCCAA($rutActa)
{
    include 'conexion.php';

    // Consulta para obtener todos los campos de ACM_owner
    $sql = "SELECT * FROM ACM_owner WHERE OWNER_CODE = '$rutActa'";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);

        if ($row) {
            // Verificamos si "CARD_NUMBER" es vacío o nulo
            if (empty($row['CARD_NUMBER'])) {
                return "Sin Credencial en SmartPSS";
            }

            if ($row['END_DATE'] <= date('Y-m-d H:i:s')) {
                return "Credencial Bloqueada Automáticamente en SmartPSS";
            } else if (strtotime($row['END_DATE']) - time() <= 20 * 24 * 60 * 60) {
                return "Credencial será bloqueada. Notificar.";
            } else {

                // Verificamos el estado de la tarjeta "CARD_STATE"
                switch ($row['CARD_STATE']) {
                    case 0:
                    case 2:
                        return "Credencial Activada en SmartPSS";
                    case 1:
                    case 3:
                        return "Credencial Bloqueada en SmartPSS";
                    default:
                        return "Estado Desconocido en SmartPSS";
                }
            }
        } else {
            return 'Estado Desconocido en SmartPSS';
        }
    } else {
        return 'Estado Desconocido en SmartPSS';
    }
}

function obtenerStatusCredencialActas($idActa)
{
    include 'conexion.php';

    // Consulta para obtener todos los campos de ACM_owner
    $sql = "SELECT bloqueoActa FROM actas WHERE idActa = '$idActa'";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = $res->fetch_assoc();
        mysqli_close($conexion);

        if ($row) {
            // Verificamos si "CARD_NUMBER" es vacío o nulo
            if ($row['bloqueoActa'] > 0) {
                return "Funcionario(a) Bloqueado(a) en Sistema de Actas";
            } else {
                return "Funcionario(a) Habilitado(a) en Sistema de Actas";
            }
        } else {
            return "Error al Cargar Información";
        }
    } else {
        return "Error al Cargar Información";
    }
}

function obtenerFechaCaducidadACM($rut)
{
    include 'conexion.php';

    $sql = "SELECT END_DATE FROM ACM_owner WHERE OWNER_CODE = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $rut);
    $stmt->execute();
    $stmt->bind_result($fechaHora);
    $stmt->fetch();
    $stmt->close();
    mysqli_close($conexion);

    // Si no hay resultado
    if (!$fechaHora) {
        return null;
    }

    // Extraemos solo la fecha sin hora
    $fecha = substr($fechaHora, 0, 10); // 2030-09-11

    // Validamos
    if (
        $fecha == "0000-00-00" ||
        $fecha == "1970-01-01" ||
        strtotime($fecha) === false
    ) {
        return null;
    }

    return $fecha; // ✅ Fecha válida en formato YYYY-MM-DD
}


function obtenerDataBloqueo($idBloqueo)
{
    include 'conexion.php';

    $sql = "SELECT * FROM actas_bloqueos WHERE idBloqueo = " . $idBloqueo;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idBloqueo'] = $row['idBloqueo'];
            $datos['folioBloqueo'] = $row['folioBloqueo'];
            $datos['fechaBloqueo'] = $row['fechaBloqueo'];
            $datos['funcionarioBloqueo'] = $row['funcionarioBloqueo'];
            $datos['rutBloqueo'] = $row['rutBloqueo'];
            $datos['actaBloqueo'] = $row['actaBloqueo'];
            $datos['creadorBloqueo'] = $row['creadorBloqueo'];
            $datos['solicitanteBloqueo'] = $row['solicitanteBloqueo'];
            $datos['detalleBloqueo'] = $row['detalleBloqueo'];
            $datos['tipoBloqueo'] = $row['tipoBloqueo'];
            return $datos;
        } else {
            return 'vacio';
        }
    } else {
        return 'vacio';
    }
}

function obtenerCantidadEquiposCC()
{
    include 'conexion.php';

    // Consulta para contar el total de equipos
    $sql = "SELECT COUNT(*) AS total FROM equipamiento_hbqp WHERE idDepaPertenece = 1";

    // Ejecutar la consulta
    $res = mysqli_query($conexion, $sql);

    if ($res) {
        // Obtener el total de equipos
        $row = mysqli_fetch_assoc($res);
        mysqli_close($conexion);

        // Verificar si se obtuvo un resultado
        if ($row) {
            return $row['total']; // Retornar el total de equipos
        } else {
            return 0; // Retornar 0 si no hay resultados
        }
    } else {
        mysqli_close($conexion);
        return 0; // Retornar 0 si hubo un error en la consulta
    }
}


function getNomEquipamiento($id)
{
    include('conexion.php');
    $query = "SELECT nombreEquipo FROM equipamiento_hbqp WHERE id='" . $id . "'";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['nombreEquipo'])) {
            $nombreEquipo = $dato['nombreEquipo'];
            if (!empty($nombreEquipo)) {
                $datoMandar = $nombreEquipo;
            } else {
                $datoMandar = 'No Aplica';
            }
        } else {
            $datoMandar = 'No Aplica';
        }
    } else {
        $datoMandar = 'No Aplica';
    }

    return $datoMandar;

}


function getInventarioEquipamiento($id)
{
    include('conexion.php');
    $query = "SELECT numeroInventario FROM equipamiento_hbqp WHERE id='" . $id . "'";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['numeroInventario'])) {
            $numeroInventario = $dato['numeroInventario'];
            if (!empty($numeroInventario)) {
                $datoMandar = $numeroInventario;
            } else {
                $datoMandar = 'No Aplica';
            }
        } else {
            $datoMandar = 'No Aplica';
        }
    } else {
        $datoMandar = 'No Aplica';
    }

    return $datoMandar;

}


function buscarSIGHTicketera($sop_id)
{

    include('conexion.php');

    $query = "SELECT COUNT(*) AS total FROM solicitudes_soportes WHERE aleatorioSolicitud = " . $sop_id;
    $res = mysqli_query($conexion, $query);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if ($total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}

function buscarUsuarioPorRUTSIGH($rut)
{
    include('conexion.php');

    $rut = mb_convert_case($rut, MB_CASE_UPPER, 'UTF-8');

    $sqlUsuarios = "SELECT jt.nomJefe AS nomEmpleado FROM jefes_tecnicos jt WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND rutJefes LIKE '" . $rut . "') UNION SELECT p.nomPersonal AS nomEmpleado FROM personal p WHERE rutPersonal LIKE '" . $rut . "';";

    $resUsuarios = mysqli_query($conexion, $sqlUsuarios);
    if ($resUsuarios) {
        $dato = mysqli_fetch_assoc($resUsuarios);
        if ($dato !== null && isset($dato['nomEmpleado'])) {
            $nomEmpleado = $dato['nomEmpleado'];
            if (!empty($nomEmpleado)) {
                $datoMandar = trim($nomEmpleado);
            } else {
                $datoMandar = 'No Aplica';
            }
        } else {
            $datoMandar = 'No Aplica';
        }
    } else {
        $datoMandar = 'No Aplica';
    }

    return $datoMandar;
}


function getProtocoloRefrigeradores($idProtocolo)
{
    include 'conexion.php';

    $sql = "SELECT * FROM protocolo_refrigeradores WHERE idProtocolo = " . $idProtocolo;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idProtocolo'] = $row['idProtocolo'];
            $datos['fechaProtocolo'] = $row['fechaProtocolo'];
            $datos['ejecutanteProtocolo'] = $row['ejecutanteProtocolo'];
            $datos['folioProtocolo'] = $row['folioProtocolo'];
            $datos['idEquipo'] = $row['idEquipo'];
            $datos['estadoEquipo'] = $row['estadoEquipo'];
            $datos['entornoEquipo'] = $row['entornoEquipo'];
            $datos['tempEquipo'] = $row['tempEquipo'];
            $datos['sonidoEquipo'] = $row['sonidoEquipo'];
            $datos['luzEquipo'] = $row['luzEquipo'];
            $datos['controlEquipo'] = $row['controlEquipo'];
            $datos['observacionApoyoClinico'] = $row['observacionApoyoClinico'];
            $datos['observacionControl'] = $row['observacionControl'];
            $datos['isAnulado'] = $row['isAnulado'];
            return $datos;
        } else {
            return 'vacio';
        }
    } else {
        return 'vacio';
    }
}


function getUltimoIDProtocoloRefrigerador()
{
    include('conexion.php');
    $query = "SELECT MAX(idProtocolo) AS ultimo_id FROM protocolo_refrigeradores;";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        $ultimo_id = isset($dato['ultimo_id']) ? $dato['ultimo_id'] : null;

        if (!is_null($ultimo_id) && $ultimo_id > 0) {
            $datoMandar = $ultimo_id;
        } else {
            $datoMandar = 0; // Cuando no hay registros en la tabla
        }
    } else {
        $datoMandar = 0; // Cuando la consulta falla
    }

    return $datoMandar;
}

function getInfoEquipamientoProtocolo($idEquipo)
{
    include 'conexion.php';

    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = " . $idEquipo;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['nombreEquipo'] = $row['nombreEquipo'];
            $datos['marca'] = $row['marca'];
            $datos['modelo'] = $row['modelo'];
            $datos['recinto'] = $row['recinto'];
            $datos['pma'] = $row['pma'];
            $datos['numeroSerie'] = $row['numeroSerie'];
            $datos['numeroInventario'] = $row['numeroInventario'];
            return $datos;
        } else {
            return 'vacio';
        }
    } else {
        return 'vacio';
    }
}

function getDatosRegistroAlarmas($id)
{
    include 'conexion.php';

    $sql = "SELECT * FROM bms_alarmas_registro WHERE idRegistro = " . $id;

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idRegistro'] = $row['idRegistro'];
            $datos['fechaRegistro'] = $row['fechaRegistro'];
            $datos['nomRegistro'] = $row['nomRegistro'];
            $datos['controlRegistro'] = $row['controlRegistro'];
            return $datos;
        } else {
            return 'vacio';
        }
    } else {
        return 'vacio';
    }
}

// Función para validar $timestamp en formato 'Y-m-d H:i:s'
function validateTimestamp($timestamp)
{
    $d = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
    return $d && $d->format('Y-m-d H:i:s') === $timestamp;
}


function buscarCategoriaRegistroAlarma($nombre)
{

    include('conexion.php');

    $query = "SELECT COUNT(*) AS total FROM bms_alarmas_categoria WHERE nomCategoria LIKE '$nombre'";
    $res = mysqli_query($conexion, $query);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if ($total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function buscarDepartamentoPorClassAlarm($class)
{
    include('conexion.php');


    $sql = "SELECT idDepartamento FROM bms_alarmas_categoria WHERE nomCategoria LIKE '" . $class . "';";

    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['idDepartamento'])) {
            $idDepartamento = $dato['idDepartamento'];
            if (!empty($idDepartamento) && $idDepartamento > 0) {
                $datoMandar = getNomDepartamento($idDepartamento);
                $datoMandar = trim($datoMandar);

// Comparar y reemplazar
                if ($datoMandar === "Instrumentación") {
                    $datoMandar = "Control Centralizado";
                } elseif ($datoMandar === "Inst. Térmicas, Climatización y Refrigeración") {
                    $datoMandar = "Climatización";
                }
            } else {
                $datoMandar = 'No Aplica';
            }
        } else {
            $datoMandar = 'No Aplica';
        }
    } else {
        $datoMandar = 'No Aplica';
    }

    return $datoMandar;
}


function contarAlarmasBMSCategoria($nombre)
{

    include('conexion.php');

    $query = "SELECT COUNT(*) AS total FROM bms_alarmas_historial WHERE classHistorial LIKE '$nombre' AND stateHistorial LIKE '%Offnormal%'";
    $res = mysqli_query($conexion, $query);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if ($total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function contarObservacionesAlarmasBMS($tipo, $id)
{

    include('conexion.php');

    if ($tipo == 'idRegistro' || $tipo == 'idHistorial') {
        if ($tipo == 'idRegistro') {
            $query = "SELECT COUNT(*) AS total FROM bms_alarmas_observacion WHERE $tipo = $id AND idHistorial = 0";

        } else if ($tipo == 'idHistorial') {
            $query = "SELECT COUNT(*) AS total FROM bms_alarmas_observacion WHERE $tipo = $id";

        }

        $res = mysqli_query($conexion, $query);
        if ($res) {
            $dato = mysqli_fetch_assoc($res);
            if ($dato !== null && isset($dato['total'])) {
                $total = $dato['total'];
                if ($total > 0) {
                    $datoMandar = $total;
                } else {
                    $datoMandar = 0;
                }
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }

    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function obtenerIDServicioControl()
{

    include('conexion.php');

    $query = "SELECT idServicio FROM servicios WHERE nomServicio LIKE 'CONTROL CENTRALIZADO'";
    $res = mysqli_query($conexion, $query);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['idServicio'])) {
            $total = $dato['idServicio'];
            if ($total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function buscarSalasMantenimiento($nombre)
{

    include('conexion.php');

    $query = "SELECT COUNT(*) as total FROM salas_electricas_comunicacion WHERE sala LIKE '$nombre' OR sala LIKE '$nombre'";
    $res = mysqli_query($conexion, $query);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if ($total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function contarHijosEquipamiento($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM equipamiento_hbqp WHERE idEquipoPadre = $id";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;

}


function contarTicketSIGHRangoFechas($rut_con_guionHT, $fechaDesde, $fechaHasta)
{
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';

    include('conexion.php');


    $query = "SELECT COUNT(*) as total FROM  ticket_sigh WHERE rutTecnicoTicketSIGH LIKE '$rut_con_guionHT' AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);

    $dato = mysqli_fetch_assoc($res);
    return $dato['total']; // Formateamos el total con comas
}


function contarfinalizadaspordepaMesAnio($rut_con_guionOT, $fechaDesde, $fechaHasta)
{
    include('conexion.php');
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE rutTecnicoTicketSIGH = '$rut_con_guionOT' AND (estadoTicketSIGH LIKE 'Finalizado') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia']; // Formateamos el total con comas

}


function contardesarrollodepaMesAnio($rut_con_guionOT, $fechaDesde, $fechaHasta)
{
    include('conexion.php');
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'En Desarrollo') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function contarpendientesdepaMesAnio($rut_con_guionOT, $fechaDesde, $fechaHasta)
{
    include('conexion.php');
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Pendiente') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function contarprogramadodepaMesAnio($rut_con_guionOT, $fechaDesde, $fechaHasta)
{
    include('conexion.php');
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Programado') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}

function contarcanceladodepaMesAnio($rut_con_guionOT, $fechaDesde, $fechaHasta)
{
    include('conexion.php');
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';
    $query = "SELECT COUNT(*) AS frecuencia FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE 'Cancelado') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['frecuencia'];
}


function buscarTicketsCategoriasMesAnio($rut_con_guionOT, $categoria, $fechaDesde, $fechaHasta)
{
    include('conexion.php');
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';
    $query = "SELECT rutTecnicoTicketSIGH, numTicketSIGH FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (estadoTicketSIGH LIKE '$categoria') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);

    $datos = array();
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $datos[] = $row;
        }
    } else {
        $datos = 'vacio';
    }

    mysqli_close($conexion);
    return $datos;
}


function contarTicketsRUTMesAnio($rut_con_guionOT, $fechaDesde, $fechaHasta)
{
    include('conexion.php');
    $fechaInicio = $fechaDesde . ' 00:00:00';
    $fechaTermino = $fechaHasta . ' 23:59:59';
    $query = "SELECT COUNT(*) as total FROM ticket_sigh WHERE (rutTecnicoTicketSIGH LIKE '$rut_con_guionOT') AND (fechaSolicTicketSIGH BETWEEN '" . $fechaInicio . "' AND '" . $fechaTermino . "')";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'];
}


function contarOTdeTareasPlanificadas($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM trabajos_general WHERE idTareaProgramada = $id";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;

}


function obtenerOTSegunTareaPlanificada($id)
{
    include('conexion.php');
    $query = "SELECT idTG FROM trabajos_general WHERE idTareaProgramada = $id";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['idTG'])) {
            $total = $dato['idTG'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;

}


function obtenerCheckListOTSegunTareaPlanificada($id)
{
    include('conexion.php');
    $query = "SELECT tipo FROM tipos_check_list WHERE idTG = $id";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['tipo'])) {
            $total = $dato['tipo'];
            if (!empty($total)) {
                $datoMandar = $total;
            } else {
                $datoMandar = '';
            }
        } else {
            $datoMandar = '';
        }
    } else {
        $datoMandar = '';
    }

    return $datoMandar;

}


function obtenerCantidadRegistrosAlarmasBMS($id)
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM bms_alarmas_historial WHERE idRegistro = $id";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;

}

function buscarArchivosLicitacion($idLicitacion)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) as total FROM archivos_licitacion_sdo WHERE idLicitacion = $idLicitacion";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}

function obtenerCantidadHTFirmadas($idOrden)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) as total FROM archivos_hoja_firmada WHERE idHoja = $idOrden";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function obtenerRutaImagenSegunModelo($modelo)
{
    include('conexion.php');
    $query = "SELECT rutaImagen FROM equipamiento_hbqp WHERE modelo LIKE '$modelo' AND (modelo NOT LIKE '') AND (rutaImagen IS NOT NULL) GROUP BY rutaImagen LIMIT 1;";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['rutaImagen'])) {
            $total = $dato['rutaImagen'];
            if (!empty($total)) {
                $datoMandar = $total;
            } else {
                $datoMandar = '';
            }
        } else {
            $datoMandar = '';
        }
    } else {
        $datoMandar = '';
    }

    return $datoMandar;

}

function obtenerCantidadNotificacionesAviso($idAviso, $usuario)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) as total FROM avisos_notificaciones WHERE idAviso = $idAviso AND usuario LIKE '$usuario' AND leida = 0";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}

function obtenerCantidadAsistenciaTurno($nomFuncionario, $fechaAsistencia, $turnoFuncionario)
{
    include('conexion.php');
    $sql = "SELECT COUNT(*) as total FROM asistencia_sdo WHERE personalAsistencia LIKE '$nomFuncionario' AND fechaAsistencia LIKE '$fechaAsistencia' AND turnoAsistencia LIKE '$turnoFuncionario'";;
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total'])) {
            $total = $dato['total'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}

function obtenerOTCartaGantt($idTareaProgramada)
{
    include('conexion.php');
    $sql = "SELECT * FROM trabajos_general WHERE idTareaProgramada = $idTareaProgramada";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['idTG'] = $row['idTG'];
            $datos['tecnico1'] = $row['tecnico1'];
            $datos['estadoTG'] = $row['estadoTG'];
            return $datos;
        } else {
            return 'vacio';
        }
    } else {
        return 'vacio';
    }
}

function normalizarRutSin0Inicio($rut)
{
    return str_replace(".", "", ltrim($rut, "0"));
}


function countAvisosPorReporteAlarmas($id)
{
    if ($id > 0) {

        include('conexion.php');
        $query = "SELECT COUNT(*) AS total FROM avisos_relevantes WHERE idReporteAlarmas = $id;";
        $res = mysqli_query($conexion, $query);

        if ($res) {
            $dato = mysqli_fetch_assoc($res);
            $total = isset($dato['total']) ? $dato['total'] : null;

            if (!is_null($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0; // Cuando no hay registros en la tabla
            }
        } else {
            $datoMandar = 0; // Cuando la consulta falla
        }
    } else {
        $datoMandar = 0; // Cuando la consulta falla
    }

    return $datoMandar;
}

function obtenerIPCliente()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Puede contener una lista de IPs: tomamos la primera (la IP real del cliente)
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }

    return $_SERVER['REMOTE_ADDR'] ?? 'IP no disponible';
}


function obtenerValorCompletoSistema($idCategoria)
{
    if ($idCategoria > 0) {

        include('conexion.php');

//        $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp WHERE idCategoria = $idCategoria AND (modelo IS NOT NULL AND modelo NOT LIKE '') AND idEquipoPadre > -1";
        $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp WHERE idCategoria = $idCategoria AND (modelo IS NOT NULL AND modelo NOT LIKE '')";
        $resInfoEquipo = mysqli_query($conexion, $sqlInfoEquipo);

        $totalTodos = 0;

        while ($row = $resInfoEquipo->fetch_assoc()) {
            $modelo = !empty($row['modelo']) ? $row['modelo'] : 'No Aplica';

            $valorConIva = obtenerMayorValorEquipamiento($modelo, $idCategoria);
            $valorMultiplicar = ($valorConIva > 0) ? $valorConIva : 0;

            $cantidad = contarModelosDistintos($modelo, $idCategoria);
            $valorMultiplicar2 = ($cantidad > 0) ? $cantidad : 0;

            $total = $valorMultiplicar * $valorMultiplicar2;
            $totalTodos += $total;
        }

        if ($totalTodos > 0) {
            $datoMandar = $totalTodos;
        } else {
            $datoMandar = 0;
        }

    } else {
        $datoMandar = 0; // Cuando la consulta falla
    }

    return $datoMandar;
}

function obtenerValorCompletoSistemaDepto($idDepartamento)
{
    include('conexion.php');

    if ($idDepartamento <= 0) {
        return 0;
    }

    $sqlObtenerCategorias = "SELECT idCategoria FROM categorias_equipos WHERE idDepartamento = $idDepartamento";
    $resObtenerCategorias = mysqli_query($conexion, $sqlObtenerCategorias);

    $sumaCompletaEquipos = 0;

    if ($resObtenerCategorias && mysqli_num_rows($resObtenerCategorias) > 0) {
        while ($rowObtenerCategorias = mysqli_fetch_assoc($resObtenerCategorias)) {
            $idCategoria = $rowObtenerCategorias['idCategoria'];

            if ($idCategoria > 0) {
//                $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp WHERE idCategoria = $idCategoria AND (modelo IS NOT NULL AND modelo NOT LIKE '') AND idEquipoPadre > -1";
                $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp WHERE idCategoria = $idCategoria AND (modelo IS NOT NULL AND modelo NOT LIKE '')";

                $resInfoEquipo = mysqli_query($conexion, $sqlInfoEquipo);

                $totalTodos = 0;

                while ($row = $resInfoEquipo->fetch_assoc()) {
                    $modelo = $row['modelo'] ?: 'No Aplica';

                    $valorConIva = max(obtenerMayorValorEquipamiento($modelo, $idCategoria), 0);
                    $cantidad = max(contarModelosDistintos($modelo, $idCategoria), 0);

                    $totalTodos += $valorConIva * $cantidad;
                }

                $sumaCompletaEquipos += $totalTodos;
            }
        }
    }

    return $sumaCompletaEquipos;
}

function crearOrdenMonitoreo($conexion, $now, $idDepartamento, $postData)
{
    include_once 'conexion.php';
    $result = [
        'success' => false,
        'mensaje' => '',
        'folioTG' => '',
        'idTG' => null
    ];

    if (!isset($postData['fechaMonitoreo'], $postData['tipoFormulario'], $postData['personaAsignada'], $postData['turnoAsignado'], $postData['creadorOT'])) {
        $result['mensaje'] = 'Faltan datos requeridos.';
        return $result;
    }

    $fechaMonitoreo = $postData['fechaMonitoreo'];
    $tipoFormulario = (int)$postData['tipoFormulario'];
    $personaAsignada = $postData['personaAsignada'];
    $turnoAsignado = (int)$postData['turnoAsignado'];
    $creadorOT = $postData['creadorOT'];
    $turnoAsignado2 = $turnoAsignado === 1 ? 'Personal Diurno' : ($turnoAsignado === 2 ? 'Personal Nocturno' : '');

    $folio = 'HBQP';
    $estado = 'Trabajo Ingresado';
    $descripcion = 'Monitoreo y Control de BMS';
    $servicioOrden = obtenerIDServicioControl();
    if ($servicioOrden <= 0) $servicioOrden = 0;

    if (!in_array($turnoAsignado, [1, 2]) || $tipoFormulario < 0) {
        $result['mensaje'] = 'Turno o tipoFormulario inválido.';
        return $result;
    }

    $detalleTurnoCamara = $detalleTurnoLLE = $detalleTurnoCA = $detalleTurnoRondas = '';
    $detalleOtros = "Ordenar herramientas y materiales utilizados en la jornada de trabajo.\nOrden general en sala de control y kitchenette de control.\nConfeccionar documento de entrega de turno (acta), con información de novedades y operatividad de los sistemas.";

    // Lógica por tipo y turno
    if ($tipoFormulario == 0) {
        $detalleTurno = 'Redactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
    } elseif ($tipoFormulario == 1 && $turnoAsignado == 1) {
        $detalleTurno = 'Revisión e inspección de consolas de alarma y tablas resúmenes de equipos de clima pisos 3 al 6 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
        $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de disyuntores trifásicos de bombas.';
    } elseif ($tipoFormulario == 1 && $turnoAsignado == 2) {
        $detalleTurno = 'Redactar correo dirigido al servicio de pabellón, adjuntando tabla con parámetros de temperatura y humedad (Pabellón N°2 al 10).\nRevisar sistema de Ticket (SIGH) y derivar al departamento que corresponda.\nRevisión e inspección de consolas de alarma y tablas resúmenes de equipos de clima pisos 3 al 6 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
        $detalleTurnoCA = 'Entrega de permisos correspondientes en software de control de accesos (formato de solicitud de permisos).';
        $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de disyuntores trifásicos de bombas.';

    } elseif ($tipoFormulario == 2 && $turnoAsignado == 1) {
        $detalleTurno = 'Revisión e inspección de parámetros de las 74 unidades manejadoras de aire y tablas resúmenes de equipos de clima pisos subterráneo al 2 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
        $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de disyuntores trifásicos de bombas.';
    } elseif ($tipoFormulario == 2 && $turnoAsignado == 2) {
        $detalleTurno = 'Revisar sistema de Ticket (SIGH) y derivar al departamento que corresponda.\nRevisión e inspección de parámetros de las 74 unidades manejadoras de aire y tablas resúmenes de equipos de clima pisos subterráneo al 2 en BMS.\nRedactar en reporte de eventos relevantes (apartado de control centralizado) el estado de los parámetros de funcionamiento de las unidades manejadoras de aire de pabellones.\nRealizar AST correspondiente al turno.';
        $detalleTurnoCA = 'Entrega de permisos correspondientes en software de control de accesos (formato de solicitud de permisos).';
        $detalleTurnoRondas = 'Realizar rondas de inspección de equipos y bombas del sistema de central frio, calor y paneles solares. Además, se debe inspeccionar la variable de temperatura en el sector de laboratorio (piso 2, sector C), UPC Adulto y UTI Infantil. El objetivo es contrastar la información del estado de funcionamiento de los equipos, inspeccionada en terreno, con lo que muestra el BMS. \nRevisar estado de disyuntores trifásicos de bombas.';
    }

    $sqlTG = "INSERT INTO trabajos_general 
        (idTG, folioTG, solicitanteTG, detalleTG, detalleTurnoCamara, detalleTurnoLLE, detalleTurnoCA, detalleTurnoRondas, detalleOtros, fechaTG, estadoTG, turnoTG, tecnico1, fechaProgramacion, ticketAsociado, tipoTrabajo, departamento, formularioRondas, descripcion, servicio) 
        VALUES (NULL, '$folio', '$creadorOT', '$detalleTurno', '$detalleTurnoCamara', '$detalleTurnoLLE', '$detalleTurnoCA', '$detalleTurnoRondas', '$detalleOtros', '$now', '$estado', '$turnoAsignado2', '$personaAsignada', '$fechaMonitoreo', 0, '', $idDepartamento, $tipoFormulario, '$descripcion', $servicioOrden)";

    if (!mysqli_query($conexion, $sqlTG)) {
        $result['mensaje'] = 'Error al insertar orden.';
        return $result;
    }

    $idTG = mysqli_insert_id($conexion);
    $result['idTG'] = $idTG;

    $idRecepcion2 = str_pad($idTG, 4, '0', STR_PAD_LEFT);
    $mapaDepto = [
        1 => 'I', 2 => 'EI', 3 => 'CLIMA', 4 => 'E', 5 => 'INFRA',
        6 => 'EQMED', 7 => 'LOG', 8 => 'MOV', 10 => 'EDAC', 12 => 'INCEDIO'
    ];
    $str = $mapaDepto[$idDepartamento] ?? 'SDO';
    $folioTG = 'OT-' . $str . '-' . $idRecepcion2;
    $result['folioTG'] = $folioTG;

    mysqli_query($conexion, "UPDATE trabajos_general SET folioTG = '$folioTG' WHERE idTG = $idTG");

    $detalleObs = "Se ha ingresado la orden de trabajo $folioTG y se ha asignado a $personaAsignada";
    $sqlAvance = "INSERT INTO linea_vida_ot (idLineaVida, nomCreador, detalle, fechaSubida, idTG)
                  VALUES (NULL, '$creadorOT', '$detalleObs', '$now', $idTG)";
    mysqli_query($conexion, $sqlAvance);

    $result['success'] = true;
    $result['mensaje'] = 'Orden creada correctamente.';
    return $result;
}

function generarLoremIpsum($cantidadParrafos)
{
    $lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
              Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
              Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
              nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
              reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
              Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia 
              deserunt mollit anim id est laborum.";

    $salida = '';
    for ($i = 0; $i < $cantidadParrafos; $i++) {
        $salida .= "<p style='text-align: justify;'>$lorem</p>\n";
    }
    return $salida;
}


function obtenerValorModeloInventario($modelo)
{
    include('conexion.php');
    $sql = "SELECT 
        AVG(valorUnitario) AS promedioModelo
    FROM inventario_equipos
    WHERE modeloInventario LIKE '$modelo'
      AND valorUnitario > 0;
    ";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['promedioModelo'])) {
            $total = $dato['promedioModelo'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function obtenerCantidadPuertasADar($id, $formato)
{
    include('conexion.php');
    $sql = "SELECT 
    SUM(door1FormCCAA +
        door2FormCCAA +
        door3FormCCAA +
        door4FormCCAA +
        door5FormCCAA +
        door6FormCCAA +
        door7FormCCAA +
        door8FormCCAA +
        door9FormCCAA +
        door10FormCCAA +
        door11FormCCAA +
        door12FormCCAA +
        door13FormCCAA +
        door14FormCCAA +
        door15FormCCAA +
        door16FormCCAA +
        door17FormCCAA +
        door18FormCCAA) AS total_general
FROM formularios_funcionarios
WHERE idUnidad = '$id' 
AND idFormRegAcceso = '$formato';
";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['total_general'])) {
            $total = $dato['total_general'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function buscaIDTicket($numTicketSIGH)
{
    include('conexion.php');
    $sql = "SELECT idSolicitud FROM solicitudes_soportes WHERE folioSolicitud LIKE '$numTicketSIGH'";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['idSolicitud'])) {
            $total = $dato['idSolicitud'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;
}


function countHTDeptoAnio($idDepto, $anio)
{
    include('conexion.php');
    $datoMandar = 0;

    // Validaciones mínimas
    if (empty($idDepto)) {
        return 0;
    }

    $anioCond = (is_numeric($anio) && $anio > 0) ? "YEAR(fechaOrden) = $anio" : "1";

    if ($idDepto === 'admin') {
        $where = $anioCond;
    } elseif (is_numeric($idDepto) && $idDepto > 0) {
        $where = "idDepartamento = $idDepto AND $anioCond";
    } else {
        return 0;
    }

    $query = "SELECT COUNT(*) AS total FROM ordenes_trabajos_general WHERE $where";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $datoMandar = !empty($row['total']) ? intval($row['total']) : 0;
    }

    return $datoMandar;
}

function countSCSDODeptoAnio($nomDepto, $anio)
{
    include('conexion.php');
    $datoMandar = 0;

    if (empty($nomDepto)) {
        return 0;
    }

    // Escapar por seguridad
    $nomDeptoFinal = mysqli_real_escape_string($conexion, $nomDepto);

    // Condición de año (si aplica)
    $anioCond = (is_numeric($anio) && $anio > 0) ? "YEAR(fechaSoliCompra) = $anio" : "1=1";

    // Condición de unidad
    if ($nomDeptoFinal === 'admin') {
        $unidadCond = "1=1"; // No filtra por unidad
    } elseif (in_array($nomDeptoFinal, ['Instrumentación', 'Control Centralizado'])) {
        $unidadCond = "(unidadSoliCompra LIKE 'Instrumentación' OR unidadSoliCompra LIKE 'Control Centralizado')";
    } else {
        $unidadCond = "unidadSoliCompra LIKE '$nomDeptoFinal'";
    }

    // Armar consulta
    $query = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE $unidadCond AND $anioCond";

    $res = mysqli_query($conexion, $query);

    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $datoMandar = !empty($row['total']) ? intval($row['total']) : 0;
    }

    return $datoMandar;
}


function countEquipamientoDepto($idDepto)
{
    include('conexion.php');
    $datoMandar = 0;

    // Validación básica
    if (empty($idDepto)) {
        return 0;
    }

    // Si es admin, contar todo
    if ($idDepto === 'admin') {
        $query = "SELECT COUNT(*) AS total FROM equipamiento_hbqp";
    } // Si es numérico, filtrar por departamento
    elseif (is_numeric($idDepto) && $idDepto > 0) {
        $query = "SELECT COUNT(*) AS total FROM equipamiento_hbqp 
                  WHERE idCategoria IN (
                      SELECT idCategoria FROM categorias_equipos WHERE idDepartamento = $idDepto
                  )";
    } else {
        return 0; // Entrada no válida
    }

    $res = mysqli_query($conexion, $query);

    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $datoMandar = !empty($row['total']) ? intval($row['total']) : 0;
    }

    return $datoMandar;
}


function countAvisosRelevantesDirigidos($nomUsuario)
{
    if (!empty($nomUsuario)) {

        include('conexion.php');
        $nomUsuarioFinal = mysqli_real_escape_string($conexion, $nomUsuario);

        $query = "SELECT COUNT(*) AS total FROM avisos_destinatarios  WHERE nomDestinatario LIKE '$nomUsuarioFinal'";

        $res = mysqli_query($conexion, $query);

        if ($res) {
            $dato = mysqli_fetch_assoc($res);
            $total = isset($dato['total']) ? $dato['total'] : null;

            if (!is_null($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0; // Cuando no hay registros en la tabla
            }
        } else {
            $datoMandar = 0; // Cuando la consulta falla
        }
    } else {
        $datoMandar = 0; // Cuando la consulta falla
    }

    return $datoMandar;
}


function countIsVisadoHT($idOrden)
{
    include('conexion.php');
    $datoMandar = 0;

    // Validación básica
    if (empty($idOrden)) {
        return 0;
    }

    if (is_numeric($idOrden) && $idOrden > 0) {
        $query = "SELECT isVisado FROM ordenes_trabajos_general WHERE idOrden = $idOrden";
    } else {
        return 0; // Entrada no válida
    }

    $res = mysqli_query($conexion, $query);

    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $datoMandar = !empty($row['isVisado']) ? (int)$row['isVisado'] : 0;
    }

    return $datoMandar;
}


function countIsVisadoOT($idTG)
{
    include('conexion.php');
    $datoMandar = 0;

    // Validación básica
    if (empty($idTG)) {
        return 0;
    }

    if (is_numeric($idTG) && $idTG > 0) {
        $query = "SELECT isVisado FROM trabajos_general WHERE idTG = $idTG";
    } else {
        return 0; // Entrada no válida
    }

    $res = mysqli_query($conexion, $query);

    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $datoMandar = !empty($row['isVisado']) ? (int)$row['isVisado'] : 0;
    }

    return $datoMandar;
}


function verificarVisadosTG($idTG)
{
    include('conexion.php');
    if (!$idTG || !is_numeric($idTG)) {
        return 1; // idTG inválido => considerar como faltante
    }

    $todoVisado = true;

    // 1. Verificar trabajos_general
    $stmt = $conexion->prepare("SELECT isVisado, folioTG FROM trabajos_general WHERE idTG = ?");
    $stmt->bind_param("i", $idTG);
    $stmt->execute();
    $stmt->bind_result($isVisadoTG, $folioTG);
    $stmt->fetch();
    $stmt->close();

    if ($isVisadoTG != 1) $todoVisado = false;

    // 2. Verificar hojas técnicas (ordenes_trabajos_general)
    $stmt = $conexion->prepare("SELECT isVisado FROM ordenes_trabajos_general WHERE noOrdenTrabajo = ?");
    $stmt->bind_param("s", $folioTG);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['isVisado'] != 1) $todoVisado = false;
    }
    $stmt->close();

    // 3. Tablas dependientes por idTG
    $tablas = [
        'datos_checklist',
        'datos_checklist_ucas',
        'datos_uma',
        'datos_checklist_logica',
        'datos_checklist_controlador_ecb',
        'datos_tcc_uma',
        'datos_vav',
        'datos_vf'
    ];

    foreach ($tablas as $tabla) {

        $stmt = $conexion->prepare("SELECT isVisado FROM $tabla WHERE idTG = ?");
        if (!$stmt) continue;

        $stmt->bind_param("i", $idTG);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if ($row['isVisado'] != 1) $todoVisado = false;
        }

        $stmt->close();
    }

    // Retorno final:
    return $todoVisado ? 0 : 1;
}


function countTotalAvisosRelevantes()
{
    include('conexion.php');
    $query = "SELECT COUNT(*) AS total FROM avisos_relevantes";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        $total = isset($dato['total']) ? $dato['total'] : null;

        if (!is_null($total) && $total > 0) {
            $datoMandar = $total;
        } else {
            $datoMandar = 0; // Cuando no hay registros en la tabla
        }
    } else {
        $datoMandar = 0; // Cuando la consulta falla
    }

    return $datoMandar;
}

function normalizarRutSinPuntosNiGuion($rut)
{
    // 1. Eliminar puntos y guiones
    $rut = str_replace(['.', '-'], '', $rut);

    // 2. Reemplazar 'k' o 'K' por '0' al final
    $rut = preg_replace('/[kK]$/', '0', $rut);

    // 3. Si tiene menos de 9 caracteres, agregar ceros a la izquierda
    $rut = str_pad($rut, 9, '0', STR_PAD_LEFT);

    return $rut;
}


function getDatosProtocoloCCAAGrupo($id)
{
    include 'conexion.php';

    if (!is_numeric($id)) {
        return 'vacio';
    }

    $stmt = $conexion->prepare("SELECT nomGrupo, fechaGrupo, descripGrupo FROM protoc_ccaa_grupo WHERE idGrupo = ?");
    if (!$stmt) {
        mysqli_close($conexion);
        return 'vacio';
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $stmt->close();
        mysqli_close($conexion);
        return $row; // <-- devuelve directamente el array asociativo
    } else {
        $stmt->close();
        mysqli_close($conexion);
        return 'vacio';
    }
}

function getDatosProtocoloCCAAPerfil($id)
{
    include 'conexion.php';

    if (!is_numeric($id)) {
        return 'vacio';
    }

    $stmt = $conexion->prepare("SELECT nomPerfil, fechaPerfil, descripPerfil FROM protoc_ccaa_perfil WHERE idPerfil = ?");
    if (!$stmt) {
        mysqli_close($conexion);
        return 'vacio';
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $stmt->close();
        mysqli_close($conexion);
        return $row; // <-- devuelve directamente el array asociativo
    } else {
        $stmt->close();
        mysqli_close($conexion);
        return 'vacio';
    }
}


function renderOpcionesPerfilesOrdenadas($conexion, $idPerfilGrupo)
{

    // Obtener combinaciones ya registradas para este grupo
    $combinacionesExistentes = [];
    $stmt = $conexion->prepare("SELECT idGrupo FROM protoc_ccaa_perfil_grupo WHERE idPerfil = ?");
    $stmt->bind_param("i", $idPerfilGrupo);
    $stmt->execute();
    $resExistentes = $stmt->get_result();

    while ($row = $resExistentes->fetch_assoc()) {
        $clave = $row['idGrupo'];
        $combinacionesExistentes[] = $clave;
    }
    $stmt->close();

    $queryGrupos = 'SELECT * FROM protoc_ccaa_grupo ORDER BY nomGrupo ASC';
    $resGrupos = mysqli_query($conexion, $queryGrupos);

    while ($row = $resGrupos->fetch_assoc()) {
        if ($row['idGrupo'] > 0) {
            $idGrupoReturn = $row['idGrupo'];
            $clave = $idGrupoReturn;

            // Omitir si ya existe en tabla
            if (in_array($clave, $combinacionesExistentes)) {
                continue;
            }

            $nomGrupoReturn = mb_convert_case($row['nomGrupo'], MB_CASE_UPPER, "UTF-8");

            echo '<option data-grupo="' . $idGrupoReturn . '">' .
                '&nbsp;&nbsp;&nbsp;' . $nomGrupoReturn .
                '</option>';

        }
    }
}

function renderOpcionesPuertasOrdenadas($conexion, $idGrupoCargar)
{
    // Obtener combinaciones ya registradas para este grupo
    $combinacionesExistentes = [];
    $stmt = $conexion->prepare("SELECT numUCA, numPuerta FROM protoc_ccaa_grupo_puerta WHERE idGrupo = ?");
    $stmt->bind_param("i", $idGrupoCargar);
    $stmt->execute();
    $resExistentes = $stmt->get_result();

    while ($row = $resExistentes->fetch_assoc()) {
        $clave = $row['numUCA'] . '_' . ($row['numPuerta'] - 1);
        $combinacionesExistentes[] = $clave;
    }
    $stmt->close();

    // Recorrer por orden de pisos
    $pisosArray = array("-P0", "-P1", "-P2", "-P3", "-P4", "-P5", "-P6");

    foreach ($pisosArray as $piso) {
        $queryUCA = 'SELECT * FROM ucas_listado WHERE name LIKE "%' . $piso . '%" ORDER BY name ASC';
        $resUCA = mysqli_query($conexion, $queryUCA);

        while ($rowUCA = $resUCA->fetch_assoc()) {
            $numUCA = $rowUCA['idUnique'];
            $nombreUCA = mb_convert_case($rowUCA['name'], MB_CASE_UPPER, "UTF-8");

            // Abrir optgroup por UCA
            echo '<optgroup label="' . $nombreUCA . '">';

            $queryPuertas = 'SELECT * FROM ACS_DOOR_GROUP_DOOR WHERE DeviceID = ' . intval($numUCA) . ' ORDER BY DoorID ASC';
            $resPuertas = mysqli_query($conexion, $queryPuertas);
            $numAcceso = 1;

            while ($row = $resPuertas->fetch_assoc()) {
                if ($row['DeviceID'] > 0 && $row['DoorID'] > -1 && $row['DoorUniqueID'] > 0) {
                    $numPuerta = $row['DoorID'];
                    $clave = $numUCA . '_' . $numPuerta;

                    // Omitir si ya existe en tabla
                    if (in_array($clave, $combinacionesExistentes)) {
                        continue;
                    }

                    $nombrePuerta = mb_convert_case(obtenerNomPuerta($row['DeviceID'], $numPuerta), MB_CASE_UPPER, "UTF-8");

                    echo '<option data-uca="' . $numUCA . '" data-puerta="' . $numPuerta . '">' .
                        '&nbsp;&nbsp;&nbsp;' . $numAcceso . '.- ' . $nombrePuerta .
                        '</option>';

                    $numAcceso++;
                }
            }

            echo '</optgroup>';
        }
    }
}


function obtenerIDTareaProgramadaSegunIDOT($id)
{
    include('conexion.php');
    $query = "SELECT idTG FROM trabajos_general WHERE idTareaProgramada = $id";
    $res = mysqli_query($conexion, $query);

    if ($res) {
        $dato = mysqli_fetch_assoc($res);
        if ($dato !== null && isset($dato['idTG'])) {
            $total = $dato['idTG'];
            if (!empty($total) && $total > 0) {
                $datoMandar = $total;
            } else {
                $datoMandar = 0;
            }
        } else {
            $datoMandar = 0;
        }
    } else {
        $datoMandar = 0;
    }

    return $datoMandar;

}

function enlaceExisteCurl($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true); // No descarga contenido
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($httpCode >= 200 && $httpCode < 400);
}

function enlaceExisteTrad($url)
{
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}


function obtenerDatosUCACheckList($id)
{
    include('conexion.php');
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $id";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {

        $row = $res->fetch_assoc();
        mysqli_close($conexion);
        if ($row) {
            $datos = array();
            $datos['id'] = $row['id'];
            $datos['nombreEquipo'] = $row['nombreEquipo'];
            return $datos;
        } else {
            return 'vacio';
        }
    } else {
        return 'vacio';
    }
}


function getDatosCredencialesRRHH($id)
{
    require 'conexion.php';

    $datos = [];

    if (!is_numeric($id)) {
        return $datos;
    }

    $stmt = $conexion->prepare("SELECT * FROM credenciales_rrhh WHERE idCredencial = ?");
    if (!$stmt) {
        return $datos;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
    }

    $stmt->close();
    $conexion->close();

    return $datos;
}

function getDatosTarjetasRRHH($id)
{
    require 'conexion.php';

    $datos = [];

    if (!is_numeric($id)) {
        return $datos;
    }

    $stmt = $conexion->prepare("SELECT * FROM credenciales_rrhh_tarjetas WHERE idTarjeta = ?");
    if (!$stmt) {
        return $datos;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
    }

    $stmt->close();
    $conexion->close();

    return $datos;
}

function getExistePerfilCredencial($idPerfil, $idCredencial)
{
    require 'conexion.php';

    $datos = [];

    if (!is_numeric($idPerfil) || !is_numeric($idCredencial)) {
        return $datos;
    }

    $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM credenciales_rrhh_registro_perfiles WHERE idCredencial = ? AND idPerfil = ?");
    if (!$stmt) {
        return $datos;
    }

    // CORREGIDO: orden correcto de parámetros
    $stmt->bind_param("ii", $idCredencial, $idPerfil);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
    }

    $stmt->close();
    $conexion->close();

    return $datos;
}

function obtenerObservacionPerfilCredencialConn(
    $conexion,
    $idPerfil,
    $idCredencial,
    $accion,
    $nomPerfil = ''
)
{
    if (!is_numeric($idPerfil) || !is_numeric($idCredencial)) {
        return 'vacio';
    }

    $accion = strtoupper(trim($accion));
    if (!in_array($accion, ['AUTORIZA', 'ANULAR'], true)) {
        return 'vacio';
    }

    // === Datos de la credencial ===
    $stmtCred = $conexion->prepare("
        SELECT nombreCredencial, apellidoCredencial
        FROM credenciales_rrhh
        WHERE idCredencial = ?
        LIMIT 1
    ");
    if (!$stmtCred) {
        return 'vacio';
    }

    $stmtCred->bind_param("i", $idCredencial);
    $stmtCred->execute();
    $resCred = $stmtCred->get_result();

    if (!$resCred || $resCred->num_rows === 0) {
        $stmtCred->close();
        return 'vacio';
    }

    $cred = $resCred->fetch_assoc();
    $stmtCred->close();

    $nombrePerfil = trim($nomPerfil);
    $nombreCompleto = trim(
        ($cred['nombreCredencial'] ?? '') . ' ' . ($cred['apellidoCredencial'] ?? '')
    );

    if ($nombrePerfil === '' || $nombreCompleto === '') {
        return 'vacio';
    }

    $nombrePerfilSeguro = htmlspecialchars($nombrePerfil, ENT_QUOTES, 'UTF-8');
    $nombreCompletoSeguro = htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8');

    if ($accion === 'AUTORIZA') {
        return 'Se AUTORIZA perfil "' . $nombrePerfilSeguro .
            '" a la credencial ID ' . $idCredencial .
            ' de ' . $nombreCompletoSeguro;
    }

    return 'Se ANULA perfil "' . $nombrePerfilSeguro .
        '" a la credencial ID ' . $idCredencial .
        ' de ' . $nombreCompletoSeguro;
}

function obtenerNombreUsuario($tipoUsuario, $idUsuario)
{

    include 'conexion.php';
    $map = [
        'administrativo' => ['adm_tecnicos', 'idAdmTecnicos', 'nomAdmTecnicos'],
        'jefe tecnico' => ['jefes_tecnicos', 'idJefe', 'nomJefe'],
        'personal' => ['personal', 'idPersonal', 'nomPersonal'],
        'administrador' => ['administracion', 'idAdministrador', 'nomAdministrador'],
        'mesa ayuda' => ['mesa_ayuda', 'idMesaAyuda', 'nomMesaAyuda'],
        'directiva' => ['directivos', 'idDirectivo', 'nomDirectivo']
    ];

    if (!isset($map[$tipoUsuario])) return null;

    [$tabla, $idCampo, $nombreCampo] = $map[$tipoUsuario];

    $sql = "SELECT $nombreCampo AS nombre FROM $tabla WHERE $idCampo = $idUsuario LIMIT 1";
    $res = mysqli_query($conexion, $sql);

    return ($res && $row = $res->fetch_assoc()) ? $row['nombre'] : null;
}

function getVariablePunto($id_tcc, $codigo_punto, $tipo_punto, $valor_punto)
{
    include 'conexion.php';

    $sql = "
        SELECT variable_punto
        FROM bms_puntos_control
        WHERE id_tcc = ?
          AND codigo_punto = ?
          AND tipo_punto = ?
          AND valor_punto = ?
        LIMIT 1
    ";

    if ($stmt = mysqli_prepare($conexion, $sql)) {

        mysqli_stmt_bind_param(
            $stmt,
            "isss",
            $id_tcc,
            $codigo_punto,
            $tipo_punto,
            $valor_punto
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $variable_punto);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            return (!empty($variable_punto)) ? $variable_punto : '';
        }

        mysqli_stmt_close($stmt);
    }

    return '';
}

function obtenerResumenMantencionesPorCarpeta($carpeta)
{
    include_once 'conexion.php';
    $tipos = ['MPI', 'MRI', 'MPE', 'MRE', 'OM', 'IS'];

    $out = [];
    foreach ($tipos as $t) {
        $out[$t] = ['cantidad' => 0, 'gasto' => 0];
    }

    // Cantidades
    $sql1 = "
        SELECT tipoMantencion AS tipo, COUNT(*) AS cantidad
        FROM ordenes_trabajos_general
        WHERE idCategoria = ?
          AND tipoMantencion IN ('MPI','MRI','MPE','MRE','OM','IS')
        GROUP BY tipoMantencion
    ";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("i", $carpeta);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    while ($r = $res1->fetch_assoc()) {
        $out[$r['tipo']]['cantidad'] = (int)$r['cantidad'];
    }
    $stmt1->close();

    // Gastos
    $sql2 = "
        SELECT tipoMantencion AS tipo, COALESCE(SUM(costoTotal),0) AS gasto
        FROM gasto_equipo_carpeta
        WHERE idCategoria = ?
          AND tipoMantencion IN ('MPI','MRI','MPE','MRE','OM','IS')
        GROUP BY tipoMantencion
    ";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("i", $carpeta);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($r = $res2->fetch_assoc()) {
        $out[$r['tipo']]['gasto'] = (int)$r['gasto'];
    }
    $stmt2->close();

    return $out;
}

function obtenerWhereEquipamientoPorCategoria($idCarpeta)
{
    $getIDCategoriaSistemaCCTV = getIDCategoriaSistemaCCTV();
    $getIDCategoriaSistemaCCAA = getIDCategoriaSistemaCCAA();
    $getIDCategoriaSistemaBMS  = getIDCategoriaSistemaBMS();

    if ($idCarpeta == $getIDCategoriaSistemaCCTV) {

        return " idCategoria = $idCarpeta ";

    } elseif ($idCarpeta == $getIDCategoriaSistemaCCAA) {

        return " idCategoria = $idCarpeta 
                 AND (modelo = 'ASC1208C-S' OR modelo = 'ASC1204C-S') ";

    } elseif ($idCarpeta == $getIDCategoriaSistemaBMS) {

        return " idCategoria = $idCarpeta
                 AND modelo LIKE 'BMS'
                 AND nombreEquipo NOT LIKE '%UMA-%'
                 AND nombreEquipo NOT LIKE '%Equipos HBQP%' ";
    }

    return " idCategoria = $idCarpeta ";
}

function obtenerUltimoMantenimientoUCA($idEquipoUcas)
{
    include "conexion.php";

    $idEquipoUcas = (int)$idEquipoUcas;

    $sql = "SELECT fecha 
            FROM datos_checklist_ucas 
            WHERE idEquipamiento = $idEquipoUcas
              AND fecha IS NOT NULL
              AND fecha <> ''
            ORDER BY fecha DESC 
            LIMIT 1";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        if (!empty($row['fecha']) && strtotime($row['fecha'])) {
            return $row['fecha']; // devolver formato original BD
        }
    }

    return 0;
}

function obtenerUltimoMantenimientoVAV($idEquipoVAV)
{
    include "conexion.php";

    $idEquipoVAV = (int)$idEquipoVAV;

    $sql = "SELECT fecha 
            FROM datos_vav 
            WHERE idEquipamiento = $idEquipoVAV
              AND fecha IS NOT NULL
              AND fecha <> ''
            ORDER BY fecha DESC 
            LIMIT 1";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        if (!empty($row['fecha']) && strtotime($row['fecha'])) {
            return $row['fecha']; // devolver formato original BD
        }
    }

    return 0;
}

function obtenerUltimoMantenimientoVF($idEquipoVF)
{
    include "conexion.php";

    $idEquipoVF = (int)$idEquipoVF;

    $sql = "SELECT fecha 
            FROM datos_vf 
            WHERE idEquipo = $idEquipoVF
              AND fecha IS NOT NULL
              AND fecha <> ''
            ORDER BY fecha DESC 
            LIMIT 1";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        if (!empty($row['fecha']) && strtotime($row['fecha'])) {
            return $row['fecha']; // devolver formato original BD
        }
    }

    return 0;
}

function obtenerTecnicoTGUCA($idTG)
{
    include "conexion.php";

    $idTG = (int)$idTG;

    $sql = "SELECT tecnico1 
            FROM trabajos_general 
            WHERE idTG = $idTG
            LIMIT 1";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        if (!empty($row['tecnico1'])) {
            return $row['tecnico1']; // devolver formato original BD
        }
    }

    return 'Sin Técnico Asignado';
}

function obtenerEstadoTGUCA($idTG)
{
    include "conexion.php";

    $idTG = (int)$idTG;

    $sql = "SELECT estadoTG 
            FROM trabajos_general 
            WHERE idTG = $idTG
            LIMIT 1";

    $res = mysqli_query($conexion, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        if (!empty($row['estadoTG'])) {
            return $row['estadoTG']; // devolver formato original BD
        }
    }

    return 'Error';
}

function obtenerRutaCompletaEquipamiento($id)
{
    $rutaFinal = '';


//    include('conexion.php');
//
//    $query = "SELECT idEquipoPadre FROM equipamiento_hbqp WHERE id = ".$id;
//    $res = mysqli_query($conexion, $query);
//
//    // Verificar si se obtuvieron resultados
//    if ($res && mysqli_num_rows($res) > 0) {
//        $dato = mysqli_fetch_assoc($res);
//
//        return $dato['idEquipoPadre'];
//    } else {
//        // Manejar el caso en que no hay resultados
//        return "No Aplica.";
//    }
}

/*
function ucassinconexion() {
    include 'conexion.php';
    $sql = "SELECT * FROM ucas_listado WHERE moduloIncendioUCA = 0 AND name NOT LIKE '%VTO%'";
    $res = mysqli_query($conexion, $sql);

    // Verificamos si se ejecutó correctamente la consulta y si se encontraron filas
    if ($res && mysqli_num_rows($res) > 0) {
        // Inicializamos un array para almacenar los datos de todas las UCAs sin conexión
        $ucassinconexion = array();

        // Iteramos sobre cada fila de resultados
        while ($row = $res->fetch_assoc()) {
            // Agregamos los datos de cada UCA sin conexión al array
            $datos = array(
                'name' => $row['name'],
                'idUnique' => $row['idUnique']
            );
            $ucassinconexion[] = $datos;
        }

        // Retornamos el array con los datos de todas las UCAs sin conexión
        return $ucassinconexion;
    } else {
        // Retornamos null si no se encontraron resultados
        return null;
    }
}
*/


/*
 function obtenerPresupuesto($id){
    include 'conexion.php';


    $sqlPresupuesto = "SELECT * FROM presupuesto_equipos WHERE idPresup = ".$id;

    $resPresupuesto = mysqli_query($conexion,$sqlPresupuesto);

    if ($resPresupuesto && mysqli_num_rows($resPresupuesto) > 0) {

        $row = $resPresupuesto->fetch_assoc();
        mysqli_close($conexion);
        if($row){
            $datos = array();
            $datos['idPresup'] = $row['idPresup'];
            $datos['folioPresup'] = $row['folioPresup'];
            $datos['creadorPresup'] = $row['creadorPresup'];
            $datos['fechaPresup'] = $row['fechaPresup'];
            $datos['unidadPresup'] = $row['unidadPresup'];
            $datos['valorPresup'] = $row['valorPresup'];
            return $datos;
        }else{
            return 'null';
        }
    }else {
        return 'null';
    }
}*/