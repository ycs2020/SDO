<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['nomUnidad'], $_POST['nomJefeSolicita'])) {

    $nomUnidad = trim($_POST['nomUnidad']);
    $nomJefeSolicita = trim($_POST['nomJefeSolicita']);

    // Verificar si los campos no están vacíos
    if (!empty($nomUnidad) && !empty($nomJefeSolicita)) {

        // Si la unidad es "Otro", retorna solo "Habilitar"
        if (strcasecmp($nomUnidad, 'otro') == 0) {
            echo 'Habilitar';
        } else {
            // Iniciar la estructura de la cadena
            $cadena = '
            <label for="exampleInputCodigoTarjeta">Nombre de Quien Solicita:<code>*</code></label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <select id="nomSolicitante" class="form-control" name="nomSolicitante">
                    <option value="">Seleccione...</option>';

            // Consulta SQL para obtener nombres de solicitantes
            $queryJefeUnidad = "SELECT DISTINCT nomSolicitante FROM actas WHERE unidadTarjeta = ? ORDER BY nomSolicitante ASC";
            $stmt = $conexion->prepare($queryJefeUnidad);
            $stmt->bind_param("s", $nomUnidad);
            $stmt->execute();
            $resJefeUnidad = $stmt->get_result();

            // Generar opciones del select
            while ($rowJefeUnidad = $resJefeUnidad->fetch_assoc()) {
                $nomJefe = mb_convert_case($rowJefeUnidad['nomSolicitante'], MB_CASE_UPPER, 'UTF-8');
                $selected = ($rowJefeUnidad['nomSolicitante'] == $nomJefeSolicita) ? 'selected' : '';
                $cadena .= "<option value='{$rowJefeUnidad['nomSolicitante']}' $selected>{$rowJefeUnidad['nomSolicitante']}</option>";
            }

            $stmt->close();

            // Opción para agregar otro nombre
            $cadena .= '<option value="OtroNombre">AGREGAR OTRO NOMBRE...</option>';
            $cadena .= '</select></div>';

            // Retornar el contenido de la cadena
            echo $cadena;
        }
    }
}
