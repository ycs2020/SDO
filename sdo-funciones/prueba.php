<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Excel de Monitoreo</title>
</head>
<body>
<h2>Subir archivo Excel de monitoreo</h2>
<form action="crear_ordenes_masivo.php" method="post" enctype="multipart/form-data">
    <label for="archivo">Seleccionar archivo Excel (.xlsx):</label><br>
    <input type="file" name="archivo" accept=".xlsx" required><br><br>

    <label for="departamento">ID Departamento:</label><br>
    <input type="number" name="idDepartamento" value="1" required><br><br>

    <input type="submit" value="Procesar Ordenes">
</form>
</body>
</html>
