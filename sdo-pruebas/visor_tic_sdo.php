<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador por Correlativo</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        input, button { padding: 8px; font-size: 16px; margin: 5px 0; }
    </style>
</head>
<body>

<h2>Buscar Solicitud por Correlativo</h2>
<button onclick="cargarDatos()">🔄 Cargar Solicitudes</button><br>
<input type="text" id="buscador" placeholder="Ej: 183/2025" oninput="filtrarSolicitudes()" disabled>

<table id="tablaSolicitudes">
    <thead>
    <tr>
        <th>Correlativo</th>
        <th>Nombre Solicitud</th>
        <th>Fecha</th>
        <th>Solicitante</th>
        <th>Unidad</th>
        <th>Etapa Actual</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    let solicitudes = [];

    function cargarDatos() {
        fetch('conexion_tic_sdo.php')
            .then(response => {
                if (!response.ok) throw new Error("HTTP: " + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success && Array.isArray(data.solicitudes)) {
                    solicitudes = data.solicitudes;
                    cargarTabla(solicitudes);
                    document.getElementById("buscador").disabled = false;

                    const est = data.estadisticas || {};
                    alert(`✅ Datos cargados correctamente.
Total solicitudes: ${est.total ?? 'N/A'}
✔️ Compras: ${est.compras ?? 'N/A'}
📄 Licitaciones: ${est.licitaciones ?? 'N/A'}
🔄 En Proceso: ${est.en_proceso ?? 'N/A'}
✅ Aprobadas: ${est.aprobadas ?? 'N/A'}
❌ Rechazadas: ${est.rechazadas ?? 'N/A'}`);
                } else {
                    alert("⚠️ La respuesta no es válida o está vacía.");
                }
            })
            .catch(error => {
                alert("❌ Error al cargar datos:\n" + error.message);
                console.error("Error:", error);
            });
    }

    function cargarTabla(data) {
        const tbody = document.querySelector("#tablaSolicitudes tbody");
        tbody.innerHTML = "";
        data.forEach(s => {
            const fila = `<tr>
          <td>${s.correlativo}</td>
          <td>${s.nombre_solicitud}</td>
          <td>${s.fecha_hora_solicitud}</td>
          <td>${s.nombre_solicitante}</td>
          <td>${s.unidad_solicitante}</td>
          <td>${s.etapa_actual}</td>
        </tr>`;
            tbody.insertAdjacentHTML('beforeend', fila);
        });
    }

    function filtrarSolicitudes() {
        const texto = document.getElementById("buscador").value.toLowerCase();
        const filtradas = solicitudes.filter(s =>
            s.correlativo.toLowerCase().includes(texto)
        );
        cargarTabla(filtradas);
    }
</script>

</body>
</html>
