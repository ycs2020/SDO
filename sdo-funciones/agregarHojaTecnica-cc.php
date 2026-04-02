<?php

include 'conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idEquipo = $_POST['idEquipo'];
    $idHojaTecnica = $_POST['idHojaTecnica'];

    if (isset($_POST['idHojaTecnica'])) {

        $sqlUpdate = "UPDATE ordenes_trabajos_general SET idEquipo = $idEquipo WHERE idOrden = $idHojaTecnica";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if ($resUpdate) {
            header('Location: ../sdo-administrador/hojastecnicas-cc.php?exitoHoja=true&&idEquipo=' . $idEquipo);
        } else {
            header('Location: ../sdo-administrador/hojastecnicas-cc.php?errorHoja=true&&idEquipo=' . $idEquipo);
        }
    } else {
        header('Location: ../sdo-administrador/hojastecnicas-cc.php?noHojaTec=true&&idEquipo=' . $idEquipo);
    }
} else {
    echo "no entre al post";
}
