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

if (isset($_POST['nomGrupo']) && isset($_POST['descripGrupo'])) {
    $nomGrupo = trim($_POST['nomGrupo']);
    $descripGrupo = trim($_POST['descripGrupo']);

    if ($nomGrupo === '' || $descripGrupo === '') {
        echo "Datos Incompletos";
        exit;
    }

    $nomGrupoNormalizado = normalizarTexto($nomGrupo);

    // Obtener todos los grupos y comparar normalizados
    $sqlBuscar = "SELECT nomGrupo FROM protoc_ccaa_grupo";
    $resultado = $conexion->query($sqlBuscar);

    while ($fila = $resultado->fetch_assoc()) {
        $existente = normalizarTexto($fila['nomGrupo']);
        if ($existente === $nomGrupoNormalizado) {
            echo "Nombre de Grupo Existente. Reintente.";
            exit;
        }
    }

    // Insertar nuevo grupo si no hay duplicados
    $sqlInsertar = "INSERT INTO protoc_ccaa_grupo (nomGrupo, descripGrupo) VALUES (?, ?)";
    $stmt = $conexion->prepare($sqlInsertar);
    $stmt->bind_param("ss", $nomGrupo, $descripGrupo);

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
