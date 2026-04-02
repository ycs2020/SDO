<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';


date_default_timezone_set('America/Santiago');

$fechaNow = date('Y-m-d H:i:s');
$fechaNowActualizar = date('Y-m-d');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}



// Configuración de la conexión a PostgreSQL
$pgsql_host = 'sigh.hsmq.cl';
$pgsql_port = '5432';
$pgsql_dbname = 'hospitalqtabp';
$pgsql_user = 'sdo';
$pgsql_password = 'c09b49683f8df1a58883f654d3ef75a9';

// Conexión a PostgreSQL

$pgsql_conn = pg_connect("host=$pgsql_host port=$pgsql_port dbname=$pgsql_dbname user=$pgsql_user password=$pgsql_password");
if (!$pgsql_conn) {
    die("Error al conectar a PostgreSQL: " . pg_last_error());
}
// Consulta en PostgreSQL
$pgsql_query = "SELECT 
    sop_id,
    f_sol.func_rut AS rut_solic,
    f_sol.func_nombre AS nomb_solic,
    cco.nombre AS nombre_uni,
    sop_serv,
    sop_fecha_solic,
    CASE 
        WHEN sop_estado_id = 1 THEN 'Activo'
        WHEN sop_estado_id = 2 THEN 'En Desarrollo'
        WHEN sop_estado_id = 3 THEN 'Pendiente'
        WHEN sop_estado_id = 4 THEN 'Finalizado'
        WHEN sop_estado_id = 5 THEN 'Programado'
        WHEN sop_estado_id = 99 THEN 'Cancelado'
    END AS estado,
    sop_fecha_tec_cierre AS fecha_cierre,
    f_tec.func_rut AS rut_tec,
    sop_tipsolic_id,
    sop_prob_id,
    f_tec.func_nombre AS nomb_tec,
    regexp_replace(regexp_replace(regexp_replace(regexp_replace(sop_detalle_solic, '<[^>]*>', '', 'g'), 'Por favor ingresar la mayor cantidad de datos necesarios y relevantes para un soporte r&aacute;pido como: \(IP del equipo, Marca, Modelo, etc.\)\.', '', 'g'), '<ul>', '', 'g'), '<li>', '', 'g') AS sop_detalle_solic
FROM 
    soporte
LEFT JOIN 
    funcionario AS f_sol ON f_sol.func_id = sop_func_id
LEFT JOIN 
    funcionario AS f_tec ON f_tec.func_id = sop_func_tec_id
LEFT JOIN 
    centro_costo_org AS cco ON cco.cco_id = sop_uni_id
LEFT JOIN 
    centro_costo_org AS cco1 ON cco1.cc_ant_id = sop_uni_id
WHERE 
    sop_fecha_reg_solic::date >= '2022-01-01' AND sop_fecha_reg_solic::date <= '$fechaNowActualizar'
    AND sop_dest_id = 1
ORDER BY 
    sop_fecha_reg_solic ASC;

";
$pgsql_result = pg_query($pgsql_conn, $pgsql_query);
if (!$pgsql_result) {
    die("Error al ejecutar la consulta en PostgreSQL: " . pg_last_error());
}

if ($pgsql_result) {
    // La consulta se ejecutó correctamente
    $num_rows = pg_num_rows($pgsql_result);

    if ($num_rows == 0) {
        header('Location: ../'.$rutaDireccion.'/dashboard-ticket-sigh.php?errorActualizarTickets=true');
    } else {

        $sqlBorrarData = "TRUNCATE TABLE ticket_sigh";

        $resBorrarData = mysqli_query($conexion,$sqlBorrarData);

        if($resBorrarData){

            $cantidadAgregado = 0;
            $cantidadError = 0;
            // Iterar sobre los resultados de PostgreSQL y insertar en MySQL
            while ($row_pgsql = pg_fetch_assoc($pgsql_result)) {
                $sop_id = $row_pgsql['sop_id'];
                $nomb_solic = $row_pgsql['nomb_solic'];
                $rut_solic = $row_pgsql['rut_solic'];
                $nombre_uni = $row_pgsql['nombre_uni'];
                $sop_serv = $row_pgsql['sop_serv'];
                $sop_fecha_solic = $row_pgsql['sop_fecha_solic'];


                $rut_tec = $row_pgsql['rut_tec'];
                $nomb_tec = $row_pgsql['nomb_tec'];
                $fecha_cierre = $row_pgsql['fecha_cierre'];
                $estado = $row_pgsql['estado'];
                $sop_detalle_solic = $row_pgsql['sop_detalle_solic'];
                $sop_tipsolic_id = $row_pgsql['sop_tipsolic_id'];
                $sop_prob_id = $row_pgsql['sop_prob_id'];

                $sop_id = !empty($row_pgsql['sop_id']) ? $row_pgsql['sop_id'] : 'No Aplica';
                $nomb_solic = !empty($row_pgsql['nomb_solic']) ? $row_pgsql['nomb_solic'] : 'No Aplica';
                $rut_solic = !empty($row_pgsql['rut_solic']) ? $row_pgsql['rut_solic'] : 'No Aplica';
                $nombre_uni = !empty($row_pgsql['nombre_uni']) ? $row_pgsql['nombre_uni'] : 'No Aplica';
                $sop_serv = !empty($row_pgsql['sop_serv']) ? $row_pgsql['sop_serv'] : 'No Aplica';
                $sop_fecha_solic = !empty($row_pgsql['sop_fecha_solic']) ? $row_pgsql['sop_fecha_solic'] : 'No Aplica';

                $rut_tec = !empty($row_pgsql['rut_tec']) ? $row_pgsql['rut_tec'] : 'No Aplica';
                $nomb_tec = !empty($row_pgsql['nomb_tec']) ? $row_pgsql['nomb_tec'] : 'No Aplica';
                $fecha_cierre = !empty($row_pgsql['fecha_cierre']) ? $row_pgsql['fecha_cierre'] : 'No Aplica';
                $estado = !empty($row_pgsql['estado']) ? $row_pgsql['estado'] : 'No Aplica';
                $sop_detalle_solic = !empty($row_pgsql['sop_detalle_solic']) ? $row_pgsql['sop_detalle_solic'] : 'No Aplica';

                if($sop_fecha_solic == 'No Aplica'){
                    $sop_fecha_solic = 'NULL';
                }else{
                    $sop_fecha_solic = cambiarFechaSlashADateTime($sop_fecha_solic);
                    $sop_fecha_solic = "'".$sop_fecha_solic."'";
                }

                if($fecha_cierre == 'No Aplica'){
                    $fecha_cierre = 'NULL';
                }else{
                    $fecha_cierre = cambiarFechaSlashADateTime($fecha_cierre);
                    $fecha_cierre = "'".$fecha_cierre."'";
                }

                $buscarDeptoSIGH = buscarDeptoSIGH($sop_tipsolic_id);
                $buscarTipoProblemaSIGH = buscarTipoProblemaSIGH($sop_prob_id);

                $sqlCargarData = "INSERT INTO ticket_sigh(idTicketSIGH, numTicketSIGH, rutTicketSIGH, nomTicketSIGH, unidadTicketSIGH, recintoTicketSIGH, destinoTicketSIGH, fechaSolicTicketSIGH, tipoSolicTicketSIGH, tipoProblemaTicketSIGH, rutTecnicoTicketSIGH, nomTecnicoTicketSIGH, fechaCierreTicketSIGH, estadoTicketSIGH, detalleTicketSIGH, fechaIngresoDatos) VALUES ";
                $sqlCargarData .= " (NULL,'".$sop_id."','".$rut_solic."','".$nomb_solic."','".$nombre_uni."','".$sop_serv."','SDO',".$sop_fecha_solic.",'".$buscarDeptoSIGH."','".$buscarTipoProblemaSIGH."','".$rut_tec."','".$nomb_tec."',".$fecha_cierre.",'".$estado."','".$sop_detalle_solic."','".$fechaNow."')";

                $resCargarData = mysqli_query($conexion,$sqlCargarData);

                if($resCargarData){
                    $cantidadAgregado = $cantidadAgregado + 1;
                    $cantidadError = $cantidadError + 0;

                }else{
                    $cantidadAgregado = $cantidadAgregado + 0;
                    $cantidadError = $cantidadError + 1;
                }

            }

            if($cantidadAgregado > 0){
                header('Location: ../'.$rutaDireccion.'/dashboard-ticket-sigh.php?exitoActualizarTickets=true');
            }else{
                header('Location: ../'.$rutaDireccion.'/dashboard-ticket-sigh.php?errorActualizarTickets=true');
            }

        }else{
            header('Location: ../'.$rutaDireccion.'/dashboard-ticket-sigh.php?errorActualizarTickets=true');
        }

    }
} else {
    header('Location: ../'.$rutaDireccion.'/dashboard-ticket-sigh.php?errorActualizarTickets=true');
}

// Cerrar conexiones
pg_close($pgsql_conn);

?>