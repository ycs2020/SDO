<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha enviado el formulario por POST

    if (isset($_POST['checklist'], $_POST['cantidad'])) {
        $checklistSeleccionado = $_POST['checklist'];
        $cantidadCheck1 = $_POST['cantidad'];
        if (!empty($checklistSeleccionado) && $cantidadCheck1 > 0) {
            for ($i = 0; $i < count($checklistSeleccionado); $i++) {
                if (!empty($checklistSeleccionado[$i])) {
                    $checklistSeleccionadoFOR = $checklistSeleccionado[$i];
                    $cantidadCheck1FOR = $cantidadCheck1[$i];
                    $sqlChecklist = "INSERT INTO `tipos_check_list`(`idTipoChecklist`, `tipo`, `cantidad`, `idTG`) 
                     VALUES (NULL,'$checklistSeleccionadoFOR',$cantidadCheck1FOR,$idTG)";
                }
            }
        }
    }
/*
    $sql = "INSERT INTO `tipos_check_list`(`idTipoChecklist`, `tipo`, `cantidad`, `idTG`) 
    VALUES (NULL,'$valor',$valorCheck1,1)";
*/
}
?>