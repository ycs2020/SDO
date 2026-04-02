<?php
// Conexión global
include_once '../sdo-funciones/conexion.php';

// Función para obtener el mayor valor con IVA de un modelo
function obtenerMayorValorEquipamiento($modelo, $idCarpeta)
{
    global $conexion; // ✅ Importante
    $query = "SELECT MAX(valorConIva) AS valorMax FROM equipamiento_hbqp 
              WHERE modelo = '$modelo' AND idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['valorMax'] ?? 0;
}

// Función para contar cuántos modelos iguales hay
function contarModelosDistintos($modelo, $idCarpeta)
{
    global $conexion; // ✅ Importante
    $query = "SELECT COUNT(*) AS total FROM equipamiento_hbqp 
              WHERE modelo = '$modelo' AND idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $query);
    $dato = mysqli_fetch_assoc($res);
    return $dato['total'] ?? 0;
}

// Función principal
function obtenerValorCompletoSistema($idCategoria)
{
    global $conexion; // ✅ Importante

    echo "Iniciando función con idCategoria: $idCategoria<br>";

    if ($idCategoria <= 0) {
        echo "ID de categoría inválido<br>";
        return 0;
    }

    $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp 
                      WHERE idCategoria = $idCategoria AND modelo IS NOT NULL AND modelo != ''";
    echo "Consulta SQL: $sqlInfoEquipo<br>";

    $resInfoEquipo = mysqli_query($conexion, $sqlInfoEquipo);

    if (!$resInfoEquipo) {
        echo "Error en la consulta: " . mysqli_error($conexion) . "<br>";
        return 0;
    }

    $totalTodos = 0;
    $contador = 0;

    while ($row = $resInfoEquipo->fetch_assoc()) {
        $contador++;
        $modelo = !empty($row['modelo']) ? $row['modelo'] : 'No Aplica';
        echo "[$contador] Modelo encontrado: $modelo<br>";

        $valorConIva = obtenerMayorValorEquipamiento($modelo, $idCategoria);
        $cantidad = contarModelosDistintos($modelo, $idCategoria);
        $subtotal = $valorConIva * $cantidad;
        $totalTodos += $subtotal;

        echo "[$contador] Valor con IVA: " . moneda_chilena($valorConIva) . "<br>";
        echo "[$contador] Cantidad: $cantidad<br>";
        echo "[$contador] Subtotal: " . moneda_chilena($subtotal) . " ($valorConIva x $cantidad)<br>";
        echo "[$contador] Total acumulado: " . moneda_chilena($totalTodos) . "<br><br>";
    }

    echo "Total final a retornar: $totalTodos<br>";
    return $totalTodos;
}

// -------------------------
// EJECUCIÓN DE PRUEBA
// -------------------------
$idCategoriaPrueba = 2; // Modifica si es necesario
$resultado = obtenerValorCompletoSistema($idCategoriaPrueba);

echo "<hr><strong>Resultado final (sin formatear):</strong> $resultado<br>";
echo "<strong>Resultado final (formateado):</strong> " . moneda_chilena($resultado);

// Formato moneda chilena
function moneda_chilena($valor)
{
    return '$ ' . number_format($valor, 0, ',', '.');
}
?>
