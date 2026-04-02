<?php
require_once '../../sdo-funciones/conexion.php'; // Ajusta la ruta
require_once '../../sdo-funciones/funciones.php'; // Ajusta la ruta

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

$estado = $_POST['estado'] ?? '';

$sql = "SELECT * FROM avisos_relevantes";
if (!empty($estado)) {

    $estado = mysqli_real_escape_string($conexion, $estado);
    $sql .= " WHERE estadoAviso LIKE '$estado'";
}
$sql .= " ORDER BY fechaAviso DESC";

$res = mysqli_query($conexion, $sql);

$html = '';
if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $id = $row['idAviso'];
        $titulo = $row['tituloAviso'];
        $fecha = strtotime($row['fechaAviso']);
        $fechaEsp = fechaEspañol2($fecha) . ' a las ' . date('H:i', $fecha) . ' hrs.';

        $estado = $row['estadoAviso'];

        $colorEstado = '';

        if($estado == 'Activo'){
            $colorEstado = 'background-color: #fff475; color: #000000;';
        }else if($estado == 'Finalizado'){
            $colorEstado = 'background-color: #b81414; color: #ffffff;';
        }else{
            $colorEstado = 'background-color: #000000; color: #ffffff;';
        }

        $cantNotificaciones = obtenerCantidadNotificacionesAviso($id, $nomUsuario);

        if($cantNotificaciones > 0){
            $styleSpanNotificaciones = 'background-color: red; color: white;';
        }else{
            $cantNotificaciones = 0;
            $styleSpanNotificaciones = 'background-color: green; color: white;';
        }

        $html .= '
<div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3">
        <button class="btn w-100 h-100 d-flex flex-column align-items-center justify-start abrirModalAviso"
        style="'.$colorEstado.'; box-shadow: 5px 5px 10px rgba(0,0,0,0.3);  position: relative;"
        data-idaviso="' . $id . '" data-toggle="modal" data-target="#modalAvisoUnico">
        <!-- Cuadro con el número en la esquina superior derecha -->
        <span style="position: absolute; top: 5px; right: 5px; font-size: 0.85rem; width: 26px; height: 26px;
             display: flex; align-items: center; justify-content: center; '.$styleSpanNotificaciones.'
             border-radius: 50%; z-index: 10;" id="notificacionesAviso'.$id.'>">
            '.$cantNotificaciones.'
        </span>

        <i class="fas fa-thumbtack"></i><br>
        <b>' . $fechaEsp . '</b><br>
        <b>' . $titulo . '</b>
    </button>
</div>';

    }
} else {
    $html = '<div class="col-12 text-center"><h4>No Existen Avisos Relevantes</h4></div>';
}

echo $html;