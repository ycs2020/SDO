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
    $proxMant = (int)$ultimoMant + 10000;
    $ultimoKm = obtenerUltimoRegistroKilometraje($idMovil);
    $diferencia = $proxMant - $ultimoKm;

    $ultimoKmFmt = number_format($ultimoKm, 0, ",", ".");
    $difFmt = number_format($diferencia, 0, ",", ".");

    // Lógica de colores para AdminLTE
    $clase = "bg-danger"; // Rojo (Vencido o crítico)
    if ($diferencia > 4999) {
        $clase = "bg-success"; // Verde (Ok)
    } elseif ($diferencia >= 3000) {
        $clase = "bg-warning"; // Amarillo (Aviso)
    }

    // 3. Generar el HTML
    if ($diferencia > 0) {
        echo '
        <div class="alert ' . $clase . ' text-center shadow-sm mb-3" role="alert" style="color: white !important;">
            <i class="fas fa-tools mr-2"></i>
            <b>Último Kilometraje: ' . $ultimoKmFmt . ' Kms. | Faltan ' . $difFmt . ' Kms. para el próximo mantenimiento.</b>
        </div>';
    } else {
        echo '
        <div class="alert bg-purple text-center shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <b>¡MANTENIMIENTO VENCIDO! Superado por ' . number_format(abs($diferencia), 0, ",", ".") . ' Kms.</b>
        </div>';
    }
} else {
    // Esto es lo que estaba pasando: el script llegaba aquí y no imprimía nada.
    // Solo para depuración en consola:
    if (isset($_POST['idMovil'])) {
        echo "";
    }
}
?>