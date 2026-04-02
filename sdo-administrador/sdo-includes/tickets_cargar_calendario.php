<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../sdo-funciones/conexion.php'; // Ajusta a tu conexión real

if (isset($_POST['anio'], $_POST['mes'])) {
    $anio = (int)$_POST['anio'];
    $mes = (int)$_POST['mes'];

    $sql = "SELECT idTicketSIGH, fechaSolicTicketSIGH, estadoTicketSIGH, solicitanteTicketSIGH, unidadSolicitante
            FROM ticket_sigh
            WHERE YEAR(fechaSolicTicketSIGH) = $anio AND MONTH(fechaSolicTicketSIGH) = $mes
            ORDER BY fechaSolicTicketSIGH ASC";

    $res = mysqli_query($conexion, $sql);

    echo "<table class='table table-bordered table-striped' id='tabla_{$anio}_{$mes}'>";
    echo "<thead class='bg-secondary text-white'><tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Solicitante</th>
            <th>Unidad</th>
          </tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td>{$row['idTicketSIGH']}</td>
                <td>" . date('d-m-Y', strtotime($row['fechaSolicTicketSIGH'])) . "</td>
                <td>{$row['estadoTicketSIGH']}</td>
                <td>{$row['solicitanteTicketSIGH']}</td>
                <td>{$row['unidadSolicitante']}</td>
              </tr>";
    }

    echo "</tbody></table>";
}
?>