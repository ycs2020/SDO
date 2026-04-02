<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../sdo-funciones/conexion.php';

if (isset($_POST['anio'], $_POST['mes'])) {
    $anio = (int)$_POST['anio'];
    $mes = (int)$_POST['mes'];

    // Consulta para obtener conteo de tickets por día
    $sql = "SELECT DATE(fechaSolicTicketSIGH) AS dia, COUNT(*) AS total
            FROM ticket_sigh
            WHERE YEAR(fechaSolicTicketSIGH) = $anio AND MONTH(fechaSolicTicketSIGH) = $mes
            GROUP BY dia";

    $res = mysqli_query($conexion, $sql);

    if (!$res) {
        die("<p class='text-danger'>Error SQL: " . mysqli_error($conexion) . "</p>");
    }

    // Preparar array con conteo por fecha
    $ticketsPorDia = [];
    $totalMensual = 0;
    while ($row = mysqli_fetch_assoc($res)) {
        $fecha = $row['dia'];
        $ticketsPorDia[$fecha] = (int)$row['total'];
        $totalMensual += (int)$row['total'];
    }

    // Preparar el calendario
    $diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    $primerDiaSemana = date('N', strtotime("$anio-$mes-01")); // 1 (Lun) a 7 (Dom)

    setlocale(LC_TIME, 'es_ES.UTF-8');
    $nombreMes = ucfirst(strftime('%B', strtotime("$anio-$mes-01")));

    // Encabezado
    echo "<div class='text-white text-center p-2 mb-2' style='background-color: #4B0082; border-radius: 5px;'>
            Mes: <strong>$nombreMes</strong> - Año: <strong>$anio</strong>
          </div>";

    echo "<div class='table-responsive'>
            <table class='table table-bordered text-center' style='table-layout: fixed; min-width: 700px;'>
                <thead class='text-white' style='background-color: #8A2BE2;'>
                    <tr>
                        <th>Lun</th>
                        <th>Mar</th>
                        <th>Mié</th>
                        <th>Jue</th>
                        <th>Vie</th>
                        <th>Sáb</th>
                        <th>Dom</th>
                    </tr>
                </thead>
                <tbody>";

    $diaActual = 1;
    $semana = 0;
    echo "<tr>";

    // Celdas vacías al inicio
    for ($i = 1; $i < $primerDiaSemana; $i++) {
        echo "<td></td>";
        $semana++;
    }

    while ($diaActual <= $diasDelMes) {
        $fechaFormateada = sprintf('%04d-%02d-%02d', $anio, $mes, $diaActual);
        $cantidad = isset($ticketsPorDia[$fechaFormateada]) ? $ticketsPorDia[$fechaFormateada] : 0;

        echo "<td style='height: 90px; vertical-align: top; background-color: #f8f9fa;'>
                <div><strong>$diaActual</strong></div>
                <div style='margin-top:5px; font-size: 0.9em;'>Cantidad Tickets: <strong>$cantidad</strong></div>
              </td>";

        $diaActual++;
        $semana++;

        // Si se completa la semana
        if ($semana % 7 == 0 && $diaActual <= $diasDelMes) {
            echo "</tr><tr>";
        }
    }

    // Celdas vacías al final
    while ($semana % 7 != 0) {
        echo "<td></td>";
        $semana++;
    }

    echo "</tr></tbody></table></div>";

    echo "<div class='text-white text-center p-2 mt-2' style='background-color: #4B0082; border-radius: 5px;'>
            Total de Tickets: <strong>$totalMensual</strong>
          </div>";

    echo "<hr><hr>";

    echo "<h4 class='text-center'>Listado de Tickets seg&uacute;n Departamento</h4>";
    echo "<br>";

    echo " <div class='row justify-content-center'>";

    $mesAnio = "$anio-$mes";

    $sqlDepa = "SELECT idDepartamento, nomDepartamento, iconoDepartamento
            FROM departamentos 
            WHERE idDepartamento NOT IN (9, 11, 13, 14, 15) 
            ORDER BY nomDepartamento ASC";

    $resDepto = mysqli_query($conexion, $sqlDepa);
    $colores = ['card-success', 'card-purple', 'card-info', 'card-danger', 'card-orange', 'card-yellow'];
    $numCard = 0;

    function contarTicketsPorRut($conexion, $rut, $mesAnio)
    {
        $sql = "SELECT COUNT(*) AS total 
            FROM ticket_sigh 
            WHERE rutTecnicoTicketSIGH = '$rut' 
              AND fechaSolicTicketSIGH BETWEEN '$mesAnio-01 00:00:00' AND '$mesAnio-31 23:59:59'";
        $res = mysqli_query($conexion, $sql);
        return $res ? (int)mysqli_fetch_assoc($res)['total'] : 0;
    }

    while ($rowDepto = $resDepto->fetch_assoc()) {
        $color = $colores[$numCard % count($colores)];
        $idDepto = $rowDepto['idDepartamento'];
        $nomDepto = $rowDepto['nomDepartamento'];
        $iconoDepto = $rowDepto['iconoDepartamento'];
        $totalDepto = 0;
        $rutsProcesados = [];

        echo "<div class='col-12 col-md-6 h-100'>
            <div class='card $color collapsed-card'>
                <div class='card-header'>
                    <h3 class='card-title'><b>$iconoDepto $nomDepto</b></h3>
                    <div class='card-tools'>
                        <button type='button' class='btn btn-tool' data-card-widget='collapse'>
                            <i class='fas fa-plus'></i>
                        </button>
                    </div>
                </div>
                <div class='card-body'>
                    <table class='table table-hover'>
                        <thead style='font-size: 12px'>
                            <tr>
                                <th class='text-center'>PERSONAL</th>
                                <th class='text-center'>CANTIDAD DE TICKETS</th>
                            </tr>
                        </thead>
                        <tbody style='font-size: 12px'>";

        // Obtener personal (funcionarios y jefes) en un solo arreglo
        $personalQuery = "
        SELECT rutPersonal AS rut, nomPersonal AS nombre, cargoPersonal AS cargo 
        FROM personal 
        WHERE departamentoPersonal = $idDepto
        AND activoPersonal = 1
        UNION
        SELECT rutJefes AS rut, nomJefe AS nombre, cargoJefe AS cargo 
        FROM jefes_tecnicos 
        WHERE idDepartamento = $idDepto 
          AND nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%'
          AND userActivado = 1";

        $resPersonal = mysqli_query($conexion, $personalQuery);

        while ($rowPersonal = $resPersonal->fetch_assoc()) {
            $rut = substr_replace($rowPersonal['rut'], '-', 8, 0); // Formato con guión
            if (in_array($rut, $rutsProcesados)) continue;

            $nombre = $rowPersonal['nombre'];
            $cargo = $rowPersonal['cargo'];
            $total = contarTicketsPorRut($conexion, $rut, $mesAnio);

            $totalDepto += $total;
            $rutsProcesados[] = $rut;

            echo "<tr>
                <td class='text-center'>$nombre ($cargo)</td>
                <td class='text-center'>$total</td>
              </tr>";
        }

        echo "          </tbody>
                        <tfoot>
                            <tr>
                                <td class='text-center'>Total</td>
                                <td class='text-center'>$totalDepto</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>";

        $numCard++;
    }
    echo "</div>";


} else {
    echo "<div class='text-white text-center p-2 mt-2' style='background-color: #d72c0d; border-radius: 5px;'>
            <strong>Error al Obtener Informaci&oacute;n</strong>
          </div>";
}
?>
