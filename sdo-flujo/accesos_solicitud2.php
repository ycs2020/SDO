<!DOCTYPE html>
<html>
<head>
    <title>Agregar filas con correlativo</title>
</head>
<body>
<table id="miTabla">
    <thead>
    <tr>
        <th>Correlativo</th>
        <th>Contenido</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    <!-- Filas existentes, si las hay -->
    </tbody>
</table>
<button id="agregarFila">Agregar Fila</button>

<script>
    const tabla = document.getElementById('miTabla');
    const btnAgregarFila = document.getElementById('agregarFila');
    let correlativo = 1;

    btnAgregarFila.addEventListener('click', () => {
        contenido = '';
        const nuevaFila = document.createElement('tr');
        for (var i = 1; i <= 5; i++) {
            contenido += '<td>Elemento ' + i + '</td>';
        }
        nuevaFila.innerHTML = contenido;


        tabla.querySelector('tbody').appendChild(nuevaFila);
        correlativo++;
    });

    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        fila.remove();
        // Actualiza los correlativos después de eliminar una fila
        correlativo--;
        actualizarCorrelativos();
    }

    function actualizarCorrelativos() {
        const filas = tabla.querySelectorAll('tbody tr');
        filas.forEach((fila, index) => {
            const correlativoCell = fila.querySelector('td:first-child');
            correlativoCell.textContent = index + 1;
        });
    }
</script>
</body>
</html>