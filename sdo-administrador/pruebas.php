<?php
// 1. Iniciar el temporizador global del script
$tiempo_inicio_total = microtime(true);

// Configuración de la conexión a PostgreSQL
$pgsql_host = 'sigh.hsmq.cl';
$pgsql_port = '5432';
$pgsql_dbname = 'hospitalqtabp';
$pgsql_user = 'sdo';
$pgsql_password = 'c09b49683f8df1a58883f654d3ef75a9';

// 2. Medir tiempo de conexión
$tiempo_inicio_conexion = microtime(true);
$pgsql_conn = pg_connect("host=$pgsql_host port=$pgsql_port dbname=$pgsql_dbname user=$pgsql_user password=$pgsql_password");
$tiempo_fin_conexion = microtime(true);

if (!$pgsql_conn) {
    die("Error al conectar a PostgreSQL: " . pg_last_error());
}

// Consulta en PostgreSQL OPTIMIZADA
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
    f_sol.func_email AS sol_email,
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
WHERE 
    sop_fecha_reg_solic >= '2022-01-01 00:00:00' 
    AND sop_fecha_reg_solic <= '2026-06-04 23:59:59'
    AND sop_dest_id = 1
ORDER BY 
    sop_fecha_reg_solic ASC;";

// 3. Medir tiempo de ejecución de la consulta
$tiempo_inicio_query = microtime(true);
$pgsql_result = pg_query($pgsql_conn, $pgsql_query);
$tiempo_fin_query = microtime(true);

if (!$pgsql_result) {
    die("Error al ejecutar la consulta en PostgreSQL: " . pg_last_error());
}

if ($pgsql_result) {
    $num_rows = pg_num_rows($pgsql_result);

    if ($num_rows == 0) {
        die("0 Rows devueltas por PostgreSQL.");
    }

    // 4. Medir el tiempo que tarda en procesar y mostrar la información (bucle HTML)
    $tiempo_inicio_render = microtime(true);

    $tabla_html = "<h2>Total de registros encontrados: " . $num_rows . "</h2>";
    $tabla_html .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse:collapse; font-family:Arial, sans-serif; font-size:12px;'>";

    // Imprimir las cabeceras de la tabla dinámicamente
    $tabla_html .= "<tr style='background-color: #eee;'>";
    for ($i = 0; $i < pg_num_fields($pgsql_result); $i++) {
        $tabla_html .= "<th>" . pg_field_name($pgsql_result, $i) . "</th>";
    }
    $tabla_html .= "</tr>";

    // Iterar y mapear cada fila de datos de PostgreSQL
    while ($row_pgsql = pg_fetch_assoc($pgsql_result)) {
        $tabla_html .= "<tr>";
        foreach ($row_pgsql as $columna => $valor) {
            $print_value = ($valor === null || $valor === '') ? '<em style="color:gray;">NULL</em>' : htmlspecialchars($valor);
            $tabla_html .= "<td>" . $print_value . "</td>";
        }
        $tabla_html .= "</tr>";
    }
    $tabla_html .= "</table>";

    $tiempo_fin_render = microtime(true);

    // 5. Cálculos finales de tiempo
    $tiempo_total_conexion = $tiempo_fin_conexion - $tiempo_inicio_conexion;
    $tiempo_total_query    = $tiempo_fin_query - $tiempo_inicio_query;
    $tiempo_total_render   = $tiempo_fin_render - $tiempo_inicio_render;
    $tiempo_total_script   = microtime(true) - $tiempo_inicio_total;

    // 6. Mostrar el reporte de rendimiento arriba
    echo "
    <div style='font-family: Arial, sans-serif; margin: 20px 0; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9; border-radius: 5px; width: fit-content;'>
        <h3 style='margin-top: 0; color: #333;'>⏱️ Reporte de Rendimiento (Optimizado)</h3>
        <hr style='border: 0; border-top: 1px solid #eee;'>
        <p><strong>Tiempo de Conexión a la BD:</strong> " . number_format($tiempo_total_conexion, 4) . " segundos</p>
        <p><strong>Tiempo de Consulta (Query):</strong> " . number_format($tiempo_total_query, 4) . " segundos</p>
        <p style='color: #e67e22;'><strong>Tiempo en Mostrar Información (Render HTML):</strong> " . number_format($tiempo_total_render, 4) . " segundos</p>
        <p style='margin-bottom: 0; color: #0066cc;'><strong>Tiempo Total del Script:</strong> " . number_format($tiempo_total_script, 4) . " segundos</p>
    </div>
    ";

    // 7. Pintar la tabla de datos abajo
    echo $tabla_html;

    // Matamos el script aquí para pruebas de rendimiento en pantalla
    exit;

} else {
    die("Error al Obtener Resultados PostgreSQL: " . pg_last_error($pgsql_conn));
}
?>