<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (isset($_POST['idCarga'])) {


    $idCarga = $_POST['idCarga'];    //
    $usuarioCarga = $_POST['usuarioCarga'];


    if (!empty($idCarga) && $idCarga > 0) {

// Consulta SQL
        $sql = "
SELECT 
    id AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    pantalla AS equipoCheck,
    especialidad AS especialidad,
    isVisado AS visado,
    'Pantalla Gráfica' AS tipoChecklist,
    '../sdo-pdf/SDO-CheckList-PantallasGraficas.php' AS redireccionVer,
    'idCheck' AS nomCheck
FROM datos_checklist 
WHERE idTG = ?

UNION ALL

SELECT 
    id AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    uca AS equipoCheck,
    'Control de Acceso' AS especialidad,
    isVisado AS visado,
    'UCAS' AS tipoChecklist,
    '../sdo-pdf/SDO-CheckList-Ucas.php' AS redireccionVer,
    'idCheck' AS nomCheck
FROM datos_checklist_ucas 
WHERE idTG = ?

UNION ALL

SELECT 
    idUma AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    controlador AS equipoCheck,
    'Unidad Manejadora de Aire' AS especialidad,
    isVisado AS visado,
    'UMA' AS tipoChecklist,
    '../sdo-pdf/SDO-CheckList-UMA.php' AS redireccionVer,
    'idUma' AS nomCheck
FROM datos_uma 
WHERE idTG = ?

UNION ALL

SELECT 
    id AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    CONCAT(controlador, ' (', ecbos, ')') AS equipoCheck,
    'Lógica de Programación' AS especialidad,
    isVisado AS visado,
    'Lógica' AS tipoChecklist,
    'Inhabilitado' AS redireccionVer,
    'Inhabilitado' AS nomCheck
FROM datos_checklist_logica 
WHERE idTG = ?

UNION ALL

SELECT 
    id AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    CONCAT(controlador, ' (', ecbos, ')') AS equipoCheck,
    'Controlador ECB' AS especialidad,
    isVisado AS visado,
    'ECB' AS tipoChecklist,
    '../sdo-pdf/SDO-CheckList-Cotrolador-ECB.php' AS redireccionVer,
    'idCheck' AS nomCheck
FROM datos_checklist_controlador_ecb 
WHERE idTG = ?

UNION ALL

SELECT 
    id AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    uma AS equipoCheck,
    'TCC UMA' AS especialidad,
    isVisado AS visado,
    'TCC UMA' AS tipoChecklist,
    'Inhabilitado' AS redireccionVer,
    'Inhabilitado' AS nomCheck
FROM datos_tcc_uma 
WHERE idTG = ?

UNION ALL

SELECT 
    idVav AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    controlador AS equipoCheck,
    'VAV' AS especialidad,
    isVisado AS visado,
    'VAV' AS tipoChecklist,
    '../sdo-pdf/SDO-CheckList-VAV.php' AS redireccionVer,
    'idVAV' AS nomCheck
FROM datos_vav 
WHERE idTG = ?

UNION ALL

SELECT 
    id AS idCheck,
    folio AS folioCheck,
    fecha AS fechaCheck,
    controlador AS equipoCheck,
    'VF' AS especialidad,
    isVisado AS visado,
    'VF' AS tipoChecklist,
    '../sdo-pdf/SDO-CheckList-VF.php' AS redireccionVer,
    'idVF' AS nomCheck
FROM datos_vf 
WHERE idTG = ?
";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iiiiiiii", $idCarga, $idCarga, $idCarga, $idCarga, $idCarga, $idCarga, $idCarga, $idCarga);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

        $numOrden = 1;
// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $idTG = $idCarga;
            $idCheck = $row['idCheck'];
            $folioCheck = $row['folioCheck'];
            $fechaCheck = $row['fechaCheck'];
            $equipoCheck = $row['equipoCheck'];
            $especialidad = $row['especialidad'];
            $isVisado = $row['visado'];
            $tipoChecklist = $row['tipoChecklist'];
            $redireccionVer = $row['redireccionVer'];
            $nomCheck = $row['nomCheck'];

            $folioCheck2 = mb_convert_case(trim($folioCheck), MB_CASE_UPPER, "UTF-8");
            $equipoCheck2 = mb_convert_case($equipoCheck, MB_CASE_UPPER, "UTF-8");
            $especialidad2 = mb_convert_case($especialidad, MB_CASE_UPPER, "UTF-8");
            $tipoChecklist2 = mb_convert_case($tipoChecklist, MB_CASE_UPPER, "UTF-8");


            $fechaCompleta = 'Fecha inválida';

            $fechaRaw = $row['fechaCheck'] ?? '';

            if (!empty($fechaRaw)) {
                $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

                if ($timestamp !== false && $timestamp > 0) {
                    $inicioFecha = date('d/m/Y', $timestamp);
                    $inicioHora  = date('H:i', $timestamp);
                    $fechaCompleta = $inicioFecha . '<br>' . $inicioHora;
                }
            }


            $buttonDescarga = '';
            $buttonVisado = '';
            $idTGEsc = htmlspecialchars($idTG, ENT_QUOTES, 'UTF-8');
            $idCheckEsc = htmlspecialchars($idCheck, ENT_QUOTES, 'UTF-8');

            $nom = htmlspecialchars($idCheckEsc, ENT_QUOTES, 'UTF-8');

            if ($idTGEsc > 0 && $idCheckEsc > 0) {
                if ($redireccionVer !== 'Inhabilitado') {
                    $buttonDescarga = "
                        <form action='$redireccionVer' target='_blank' method='POST'>
                            <input type='hidden' name='idTG' id='idTG' value='$idTGEsc'>
                            <input type='hidden' name='$nomCheck' id='$nomCheck' value='$idCheckEsc'>
                            <button type='submit' class='btn btn-sm btn-block bg-primary'>
                                <i class='fas fa-eye'></i>
                            </button>
                        </form>
                    ";

                    if($isVisado > 0){
                        $buttonVisado = "<span class='text-muted'>Checklist Visado</span>";
                    }else{
                        $buttonVisado = "<button class='btn btn-warning btn-sm btn-block btn-visarcheck' data-idot='$idTG' data-idcheck='$idCheck' data-tipocheck='$tipoChecklist' data-visador='$usuarioCarga' ><i class='fas fa-file-signature'></i></button>";
                    }
//                    $buttonVisado = "<button class='btn btn-warning btn-sm btn-block btn-visarcheck' data-idot='$idTG' data-idcheck='$idCheck' data-tipocheck='$tipoChecklist' data-visador='$usuarioCarga' ><i class='fas fa-file-signature'></i></button>";

//                    $buttonVisado = "UPDATE $tipoChecklist SET isVisado = 1, nombreVisado = '$usuarioCarga', fechaVisado = NOW() WHERE idTG = '$idTG' AND (isVisado = 0 OR isVisado IS NULL)";

                } else {
                    $buttonDescarga = "<span class='text-muted'>Sin Checklist</span>";
                    $buttonVisado = "<span class='text-muted'>Sin Checklist</span>";
                }


            } else {
                $buttonDescarga = "<span class='text-muted'>Sin Checklist</span>";
                $buttonVisado = "<span class='text-muted'>Sin Checklist</span>";
            }

            $row['numOrden'] = $numOrden;
            $row['idTG'] = $idTG;
            $row['idCheck'] = $idCheck;
            $row['folioCheck'] = $folioCheck;
            $row['fechaCheck'] = $fechaCheck;
            $row['equipoCheck'] = $equipoCheck;
            $row['especialidad'] = $especialidad;
            $row['isVisado'] = $isVisado;
            $row['tipoChecklist'] = $tipoChecklist;
            $row['fechaCompleta'] = $fechaCompleta;
            $row['fechaRaw'] = $fechaRaw;
            $row['numOrden'] = $numOrden;
            $row['folioCheck2'] = $folioCheck2;
            $row['equipoCheck2'] = $equipoCheck2;
            $row['especialidad2'] = $especialidad2;
            $row['tipoChecklist2'] = $tipoChecklist2;
            $row['redireccionVer'] = $redireccionVer;
            $row['nomCheck'] = $nomCheck;
            $row['buttonDescarga'] = $buttonDescarga;
            $row['buttonVisado'] = $buttonVisado;

            $numOrden++;


            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);
    } else {
        return '';
    }

} else {
    return '';
}

?>