<?php
// Archivo: ajax-listado-sc-sdo.php (ejemplo de nombre)

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificación de entrada
if (isset($_POST['nomDeptoEnviar']) && !empty(trim($_POST['nomDeptoEnviar']))) {

    $nomDeptoEnviar = trim($_POST['nomDeptoEnviar']);
    $params = [];
    $types = "";

    // 2. Construcción de SQL seguro con Marcadores de Posición (?)
    if ($nomDeptoEnviar == 'Equipos Industriales') {
        $sql = "SELECT * FROM solicitudes_compra_sdo 
                WHERE unidadSoliCompra IN ('Equipos Industriales', 'Equipos de Apoyo Clínico', 'Incendio y Seguridad') 
                ORDER BY idSoliCompra DESC";
    } else if ($nomDeptoEnviar == 'Instrumentación' || $nomDeptoEnviar == 'Control Centralizado') {
        $sql = "SELECT * FROM solicitudes_compra_sdo 
                WHERE unidadSoliCompra IN ('Control Centralizado', 'Instrumentación') 
                ORDER BY idSoliCompra DESC";
    } else if ($nomDeptoEnviar == 'Incendio y Seguridad') {
        $sql = "SELECT * FROM solicitudes_compra_sdo 
                WHERE unidadSoliCompra IN ('Equipos Industriales', 'Incendio y Seguridad') 
                ORDER BY idSoliCompra DESC";
    } else {
        // MEJORA CRÍTICA: Usamos un marcador ? para evitar inyección
        $sql = "SELECT * FROM solicitudes_compra_sdo WHERE unidadSoliCompra = ? ORDER BY idSoliCompra DESC";
        $params[] = $nomDeptoEnviar;
        $types = "s"; // "s" significa que el parámetro es un string
    }

    $stmt = $conexion->prepare($sql);

    // 3. Vincular parámetros dinámicamente si existen
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $datos = array();

    $nomUsuario2 = $_SESSION['nomUsuario'] ?? 'No Aplica';

    while ($row = $result->fetch_assoc()) {
        // --- PROCESAMIENTO DE DATOS ---

        // Fecha y Hora
        $fechaOriginal = str_replace('/', '-', $row["fechaSoliCompra"]);
        $row["fechaSoliCompra"] = date('d/m/Y', strtotime($fechaOriginal)) . '<br>' . date('H:i', strtotime($fechaOriginal)) . ' hrs.';

        // Lógica de Personal y Unidad (Mejorada)
        $personal = mb_convert_case(trim($row["personalSoliCompra"]), MB_CASE_UPPER, 'UTF-8');
        $unidad   = mb_convert_case(trim($row["unidadSoliCompra"]), MB_CASE_UPPER, 'UTF-8');

        $partes = explode('(', $personal);
        $nombrePrincipal = trim($partes[0]);
        $infoAdicional = (isset($partes[1])) ? trim($partes[1], ' )') : '';

        $row["personalSoliCompra"] = ($infoAdicional !== '')
            ? "$nombrePrincipal<br>($infoAdicional)<br>($unidad)"
            : "$nombrePrincipal<br>($unidad)";

        // Limpieza de descripción y estado
        $row["descripcionSoliCompra"] = mb_convert_case(($row["descripcionSoliCompra"] ?: 'No Aplica'), MB_CASE_UPPER, 'UTF-8');
        $row["estadoSoliCompra"] = mb_convert_case($row["estadoSoliCompra"], MB_CASE_UPPER, 'UTF-8');

        // Lógica de revisión Secretaria
        $row["opcion2"] = ($nomUsuario2 == 'Carola Avila' && $row["estadoSoliCompra"] == 'SOLICITADA A SECRETARIA SDO' && $row["isRevisado"] == 0)
            ? 'revisadoSecre'
            : '1';

        // Ticket SIGH
        $ticketTxt = ($row["numTicketSIGH"] > 0) ? "TICKET SIGH: N° {$row["numTicketSIGH"]}" : "TICKET SIGH: NO APLICA.";
        $row['textoTicket'] = mb_convert_case($ticketTxt, MB_CASE_UPPER, 'UTF-8');

        $row['contarCotizacion'] = contarCotizacionesSoliCompraSDO($row["idSoliCompra"]);
        $row["nomUsuarioVisual"] = $nomUsuario2;

        // --- LÓGICA DE BOTÓN SIGH DINÁMICO (DENTRO DEL WHILE) ---
        $scSigh = trim($row["scSigh"] ?? '');
        $anioSC = date('Y', strtotime(str_replace('/', '-', $row["fechaSoliCompra"])));
        $idSoli = $row["idSoliCompra"];

        if (!empty($scSigh) && $scSigh !== 'SIN CODIGO SIGH') {
            // 1. Normalización (relleno de ceros)
            $segmentos = explode('/', $scSigh);
            if (strlen($segmentos[0]) < 3) {
                $segmentos[0] = str_pad($segmentos[0], 3, '0', STR_PAD_LEFT);
            }
            $scSighNormalizado = $segmentos[0] . (isset($segmentos[1]) ? '/' . $segmentos[1] : '');

            // 2. Agregar año si falta
            if (strpos($scSighNormalizado, '/') === false) {
                $scSighNormalizado .= "/" . $anioSC;
            }

            // Botón de Búsqueda/Modificación (Verde)
            $row['botonSigh'] = '<button class="btn btn-xs btn-success btn-buscar" 
                            data-correlativo="'.$scSighNormalizado.'" 
                            data-id="'.$idSoli.'">
                            <i class="fa fa-search"></i> '.$scSighNormalizado.'
                         </button>';
        } else {
            // Botón de Agregar (Amarillo)
            $row['botonSigh'] = '<button class="btn btn-xs btn-warning btn-buscar" 
                            data-correlativo="" 
                            data-id="'.$idSoli.'">
                            <i class="fa fa-plus"></i> AGREGAR SIGH
                         </button>';
        }

        $datos[] = $row;
    }

    $stmt->close();
    $conexion->close();

    header('Content-Type: application/json');
    echo json_encode($datos);

} else {
    echo json_encode([]);
}