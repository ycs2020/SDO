<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT * FROM credenciales_rrhh 
        ORDER BY apellidoCredencial ASC, nombreCredencial ASC";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

$esRRHH = isset($_POST['esRRHH']) ? $_POST['esRRHH'] : 'NO';

while ($row = $result->fetch_assoc()) {

    $idCredencial = (int)$row["idCredencial"];

    $sqlContrato = "SELECT terminoContrato
                FROM credenciales_rrhh_contratos
                WHERE idCredencial = ?
                ORDER BY idContrato DESC
                LIMIT 1";

    $stmtContrato = $conexion->prepare($sqlContrato);

    $stmtContrato->bind_param("i", $idCredencial);

    $stmtContrato->execute();

    $resultContrato = $stmtContrato->get_result();

    $rowContrato = $resultContrato->fetch_assoc();

    $estadoContrato = "<span class='badge badge-success'>Contrato Vigente</span>";

    /* =========================================
       CONSULTAR ESTADO DE TARJETA
    ========================================= */

    $sqlTarjetas = "SELECT *
                FROM credenciales_rrhh_registro_tarjetas
                WHERE idCredencial = ?
                ORDER BY fechaRegistro DESC
                LIMIT 1";

    $stmtTarjetas = $conexion->prepare($sqlTarjetas);

    $stmtTarjetas->bind_param("i", $idCredencial);

    $stmtTarjetas->execute();

    $resultTarjetas = $stmtTarjetas->get_result();

    $rowTarjetas = $resultTarjetas->fetch_assoc();

    $activacionCredencialNuevo = (int)($rowTarjetas['activacionCredencial'] ?? 0);

    /* =========================================
       VALIDAR CONTRATO
    ========================================= */

    if ($rowContrato && !empty($rowContrato['terminoContrato'])) {

        $fechaTermino = new DateTime($rowContrato['terminoContrato']);
        $fechaActual  = new DateTime();

        $diasRestantes = (int)$fechaActual->diff($fechaTermino)->format('%r%a');

        if ($diasRestantes < 0) {

            if ($activacionCredencialNuevo === 0) {
                $estadoContrato = "
                <span class='badge badge-danger'>
                    Contrato Vencido
                </span>
            ";
            } else {
                $estadoContrato = "
                <span class='badge badge-danger'>
                    Contrato vencido - Se requiere bloqueo de credencial
                </span>
            ";
            }

        } elseif ($diasRestantes <= 30) {

            $estadoContrato = "
            <span class='badge badge-warning'>
                Contrato por vencer ({$diasRestantes} días)
            </span>
        ";

        } else {

            if ($activacionCredencialNuevo === 0) {
                $estadoContrato = "
                <span class='badge badge-secondary'>
                    Credencial inactiva con contrato vigente
                </span>
            ";
            } else {
                $estadoContrato = "
                <span class='badge badge-success'>
                    Contrato Vigente
                </span>
            ";
            }
        }

    } else {

        // 🔴 CASO SIN CONTRATO
        $estadoContrato = "
        <span class='badge badge-dark'>
            Sin contrato registrado
        </span>
    ";
    }

    $sqlRRHH = "
    SELECT COUNT(*) AS pendientes
    FROM credenciales_rrhh_observaciones
    WHERE idCredencial = ?
    AND notificado = 0
    AND tipoObservacion IN ('Trabajo Realizado')
";

    $stmtRRHH = $conexion->prepare($sqlRRHH);
    $stmtRRHH->bind_param("i", $idCredencial);
    $stmtRRHH->execute();
    $rowRRHH = $stmtRRHH->get_result()->fetch_assoc();


    $sqlOtros = "
    SELECT COUNT(*) AS pendientes
    FROM credenciales_rrhh_observaciones
    WHERE idCredencial = ?
    AND notificado = 0
    AND tipoObservacion NOT IN ('Creacion de Orden de Trabajo', 'Trabajo Realizado')
";

    $stmtOtros = $conexion->prepare($sqlOtros);
    $stmtOtros->bind_param("i", $idCredencial);
    $stmtOtros->execute();
    $rowOtros = $stmtOtros->get_result()->fetch_assoc();


    if($esRRHH == 'SI'){
        $total = $rowRRHH['pendientes'] ?? 0;
    }else{
        $total = $rowOtros['pendientes'] ?? 0;
    }

    $stmtRRHH->close();
    $stmtOtros->close();


    $sql2 = "SELECT * 
         FROM credenciales_rrhh_registro_tarjetas 
         WHERE idCredencial = ?
         ORDER BY fechaRegistro DESC
         LIMIT 1";

    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("i", $idCredencial);
    $stmt2->execute();

    $result2 = $stmt2->get_result();

    $estadoCredencial = "<span class='badge badge-danger'>Inactivo</span>";

    $row2 = $result2->fetch_assoc();

    if ($row2) {

        $activacionCredencial = (int)$row2["activacionCredencial"];

        if ($activacionCredencial === 1) {

            $estadoCredencial = "<span class='badge badge-success'>Activo</span>";

        } elseif ($activacionCredencial === 2) {

            $estadoCredencial = "<span class='badge badge-warning'>Por Habilitar</span>";

        } elseif ($activacionCredencial === 3) {

            $estadoCredencial = "<span class='badge badge-primary'>Por Deshabilitar</span>";
        }else{

            $estadoCredencial = "<span class='badge badge-danger'>Inactivo</span>";
        }
    }

    $data[] = [

        "nombreCredencial"   => $row["nombreCredencial"],
        "apellidoCredencial" => $row["apellidoCredencial"],
        "rutCredencial"      => $row["rutCredencial"],
        "telefonoCredencial" => $row["telefonoCredencial"],
        "estadoContrato"     => $estadoContrato,
        "activacionCredencial" => $estadoCredencial,

        "notificaciones" => "

" . (
            $total > 0
                ? "<span class='badge badge-danger'>
            {$total} novedades
       </span>"
                : "<span class='badge badge-success'>
            Sin novedades
       </span>"
            ) . "
",

        "buttonVisualizar"   => ($idCredencial > 0)
            ? "<button class='btn btn-primary btn-sm btn-block btn-vercred' data-idcred='{$idCredencial}'>
                <i class='fas fa-eye'></i>
           </button>"
            : "<span class='text-muted'>Deshabilitado</span>"
    ];

    $stmt2->close();
}

$stmt->close();
$conexion->close();

echo json_encode([
    "data" => $data
]);

exit;
?>