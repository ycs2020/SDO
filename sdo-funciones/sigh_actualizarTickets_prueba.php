<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Parámetros de conexión
$pgsql_host = 'sigh.hsmq.cl';
$pgsql_port = '5432';
$pgsql_dbname = 'hospitalqtabp';
$pgsql_user = 'sdo';
$pgsql_password = 'c09b49683f8df1a58883f654d3ef75a9';

// Intentar conexión
$pgsql_conn = pg_connect("host=$pgsql_host port=$pgsql_port dbname=$pgsql_dbname user=$pgsql_user password=$pgsql_password");

if (!$pgsql_conn) {
    die("❌ Error al conectar a PostgreSQL: " . pg_last_error());
} else {
    echo "✅ Conexión exitosa a PostgreSQL<br>";
    // Ejecutar una consulta básica para probar
    $result = pg_query($pgsql_conn, "SELECT NOW()");
    if ($result) {
        $row = pg_fetch_assoc($result);
        echo "🕒 Fecha/hora actual desde PostgreSQL: " . $row['now'];
    } else {
        echo "⚠️ Consulta de prueba falló: " . pg_last_error();
    }

    // Cerrar conexión
    pg_close($pgsql_conn);
}
?>
