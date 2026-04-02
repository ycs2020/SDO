<?php
$hostname = 'localhost';
$username = 'adminSDO';
$password = 'RmR2vnBnYouHh4Y';
$database = 'sistema_sdo_2';

$mysqli = new mysqli($hostname, $username, $password, $database);
$mysqli->set_charset("utf8");

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$nombreVisado = 'Yorgo Casimis Subiabre';
$fechaVisado = '2025-09-05 08:00:00';

// Buscar trabajos no visados de Yorgo terminados
$sql = "SELECT idTG, folioTG, idTareaProgramada 
        FROM trabajos_general 
        WHERE isVisado = 0 
          AND estadoTG = 'Trabajo Terminado' 
          AND solicitanteTG LIKE '%Yorgo Casimis%'";

$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<h3>✔ Visado de trabajos realizado:</h3>";

    while ($row = $result->fetch_assoc()) {
        $idTG = $row['idTG'];
        $folioTG = $row['folioTG'];
        $idTareaP = (int)$row['idTareaProgramada'];

        // Tablas por idTG
        $tablas = [
            'trabajos_general',
            'datos_checklist',
            'datos_checklist_controlador_ecb',
            'datos_checklist_logica',
            'datos_checklist_ucas',
            'datos_tcc_uma',
            'datos_uma',
            'datos_vav',
            'datos_vf'
        ];

        foreach ($tablas as $tabla) {
            $stmt = $mysqli->prepare("UPDATE $tabla 
                                      SET isVisado = 1, nombreVisado = ?, fechaVisado = ? 
                                      WHERE idTG = ? AND (isVisado IS NULL OR isVisado = 0)");
            if ($stmt) {
                $stmt->bind_param("ssi", $nombreVisado, $fechaVisado, $idTG);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Visar orden de trabajo
        $stmtOT = $mysqli->prepare("UPDATE ordenes_trabajos_general 
                                    SET isVisado = 1, nombreVisado = ?, fechaVisado = ? 
                                    WHERE noOrdenTrabajo = ? AND (isVisado IS NULL OR isVisado = 0)");
        if ($stmtOT) {
            $stmtOT->bind_param("sss", $nombreVisado, $fechaVisado, $folioTG);
            $stmtOT->execute();
            $stmtOT->close();
        }

        // ACTUALIZAR TAREA (si tiene)
        if ($idTareaP > 0) {
            $consultaTarea = $mysqli->prepare("SELECT inicioRealTareaP, inicioPlanTareaP 
                                               FROM tareas_carta_gantt 
                                               WHERE idTareaP = ?");
            $consultaTarea->bind_param("i", $idTareaP);
            $consultaTarea->execute();
            $consultaTarea->bind_result($inicioReal, $inicioPlan);
            $inicioRealTarea = null;

            if ($consultaTarea->fetch()) {
                $inicioRealTarea = empty($inicioReal) ? $inicioPlan : $inicioReal;
            }
            $consultaTarea->close();

            if (!empty($inicioRealTarea)) {
                $inicioDate = new DateTime($inicioRealTarea);
                $finRealDate = clone $inicioDate;
                $finRealDate->modify('+1 day');

                $inicioStr = $inicioDate->format('Y-m-d');
                $finStr = $finRealDate->format('Y-m-d');

                $stmtTarea = $mysqli->prepare("UPDATE tareas_carta_gantt 
                    SET inicioRealTareaP = ?, 
                        finRealTareaP = ?, 
                        porcentajeTareaP = 100, 
                        finalizadaTareaP = 1 
                    WHERE idTareaP = ?");
                $stmtTarea->bind_param("ssi", $inicioStr, $finStr, $idTareaP);
                $stmtTarea->execute();
                $stmtTarea->close();

                echo "<div>📌 Tarea idTareaP $idTareaP actualizada: inicio $inicioStr - fin $finStr</div>";
            }
        }

        echo "<div>✔ Visado aplicado a <b>idTG:</b> $idTG | <b>folio:</b> $folioTG</div>";
    }
} else {
    echo "<p>🔍 No hay trabajos pendientes de visado para Yorgo Casimis.</p>";
}

$mysqli->close();
?>
