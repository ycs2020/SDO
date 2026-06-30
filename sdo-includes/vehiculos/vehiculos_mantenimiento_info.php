<?php
/**
 * Este archivo se usa tanto para el include inicial como para actualizaciones AJAX
 */

// 1. Verificar si la conexión existe. Si no, estamos en una petición AJAX y debemos cargar dependencias.
if (!isset($conexion)) {
    // Ajusta estas rutas según tu estructura real (subir niveles hasta llegar a sdo-funciones)
    require_once("../../sdo-funciones/conexion.php");
    require_once("../../sdo-funciones/funciones.php");

    // En AJAX, el ID viene por POST
    $idMovil = isset($_POST['idMovil']) ? (int)$_POST['idMovil'] : 0;
} else {
    // Si ya existe $conexion (vía include), el $idMovil ya debería estar definido en el archivo padre
    // Pero por seguridad, si no existe, lo buscamos en la sesión
    if (!isset($idMovil) || empty($idMovil)) {
        $idMovil = isset($_SESSION['idMovil']) ? $_SESSION['idMovil'] : 0;
    }
}

// 2. Solo ejecutar si tenemos un ID válido
if ($idMovil > 0) {

    $ultimoMant = obtenerUltimoRegistroMantenimiento($idMovil);
    $proxMant   = (int)$ultimoMant + 10000;
    $ultimoKm   = obtenerUltimoRegistroKilometraje($idMovil);
    $diferencia = $proxMant - $ultimoKm;

// Formateo de números de forma limpia
    $ultimoKmFmt = number_format($ultimoKm, 0, ",", ".");
    $difFmt      = number_format(abs($diferencia), 0, ",", ".");

// 1. Determinar el estado y la clase de AdminLTE según el kilometraje restante
    if ($diferencia < 0) {
        // Ya se pasó del kilometraje establecido
        $clase = "bg-purple";
        $icono = "fas fa-exclamation-triangle";
        $mensaje = "<b>¡MANTENIMIENTO VENCIDO!</b> Superado por {$difFmt} Kms. (Último KM: {$ultimoKmFmt})";
    } elseif ($diferencia === 0) {
        // Justo en el límite
        $clase = "bg-danger";
        $icono = "fas fa-stopwatch";
        $mensaje = "<b>¡MANTENIMIENTO CRÍTICO!</b> Debe realizarse hoy mismo. (Último KM: {$ultimoKmFmt})";
    } elseif ($diferencia <= 1500) {
        // Muy cerca de vencer (Menos de 1.500 km)
        $clase = "bg-danger";
        $icono = "fas fa-tools";
        $mensaje = "Último KM: {$ultimoKmFmt} | <b>¡Falta muy poco! Solo restan {$difFmt} Kms.</b> para el próximo mantenimiento.";
    } elseif ($diferencia <= 4000) {
        // Rango de advertencia (Entre 1.501 y 4.000 km)
        $clase = "bg-warning";
        $icono = "fas fa-info-circle";
        $mensaje = "Último KM: {$ultimoKmFmt} | Faltan {$difFmt} Kms. para el próximo mantenimiento.";
    } else {
        // (Más de 4.000 km disponibles)
        $clase = "bg-success";
        $icono = "fas fa-check-circle";
        $mensaje = "Último KM: {$ultimoKmFmt} | Estado óptimo. Faltan {$difFmt} Kms. para el próximo mantenimiento.";
    }

// 2. Renderizar un único bloque HTML limpio
    echo "
<div class='alert {$clase} text-center shadow-sm mb-3' role='alert'>
    <i class='{$icono} mr-2'></i>
    {$mensaje}
</div>";
} else {
    // Esto es lo que estaba pasando: el script llegaba aquí y no imprimía nada.
    // Solo para depuración en consola:
    if (isset($_POST['idMovil'])) {
        echo "";
    }
}
?>