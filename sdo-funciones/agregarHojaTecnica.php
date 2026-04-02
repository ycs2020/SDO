<?php

include 'conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idHoja = $_POST['idHojaPadre'];
    $idHojaTecnica = $_POST['idHojaTecnica'];

    if (isset($_POST['idHojaTecnica'])) {

        $sqlUpdate = "UPDATE ordenes_trabajos_general SET idEquipo = $idHoja WHERE idOrden = $idHojaTecnica";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if ($resUpdate) {
            header('Location: ../sdo-administrador/hojastecnicas.php?exitoHoja=true&&idHoja=' . $idHoja);
        } else {
            header('Location: ../sdo-administrador/hojastecnicas.php?errorHoja=true&&idHoja=' . $idHoja);
        }
    } else {
        header('Location: ../sdo-administrador/hojastecnicas.php?noHojaTec=true&&idHoja=' . $idHoja);
    }
} else {
    echo "no entre al post";
}
