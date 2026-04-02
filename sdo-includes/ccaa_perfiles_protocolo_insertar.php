<?php
include '../sdo-funciones/conexion.php';

function normalizarTexto($texto) {
    // Pasar a minúsculas
    $texto = mb_strtolower($texto, 'UTF-8');

    // Eliminar tildes y caracteres especiales
    $texto = strtr($texto, [
        'á' => 'a', 'é' => 'e', 'í' => 'i',
        'ó' => 'o', 'ú' => 'u', 'ñ' => 'n',
        'ä' => 'a', 'ë' => 'e', 'ï' => 'i',
        'ö' => 'o', 'ü' => 'u'
    ]);

    // Quitar dobles espacios y recortar bordes
    $texto = trim(preg_replace('/\s+/', ' ', $texto));

    return $texto;
}

if (isset($_POST['nomPerfil']) && isset($_POST['descripPerfil'])) {
    $nomPerfil = trim($_POST['nomPerfil']);
    $descripPerfil = trim($_POST['descripPerfil']);

    if ($nomPerfil === '' || $descripPerfil === '') {
        echo "Datos Incompletos";
        exit;
    }

    $nomPerfilNormalizado = normalizarTexto($nomPerfil);

    // Obtener todos los perfiles y comparar normalizados
    $sqlBuscar = "SELECT nomPerfil FROM protoc_ccaa_perfil";
    $resultado = $conexion->query($sqlBuscar);

    while ($fila = $resultado->fetch_assoc()) {
        $existente = normalizarTexto($fila['$nomPerfil']);
        if ($existente === $nomPerfilNormalizado) {
            echo "Nombre de Perfil Existente. Reintente.";
            exit;
        }
    }

    // Insertar nuevo perfil si no hay duplicados
    $sqlInsertar = "INSERT INTO protoc_ccaa_perfil (nomPerfil, descripPerfil) VALUES (?, ?)";
    $stmt = $conexion->prepare($sqlInsertar);
    $nomPerfilNormalizado = mb_convert_case($nomPerfilNormalizado,MB_CASE_TITLE,'UTF-8');
    $stmt->bind_param("ss", $nomPerfilNormalizado, $descripPerfil);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "Error al insertar: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Datos No Recibidos";
}
?>
