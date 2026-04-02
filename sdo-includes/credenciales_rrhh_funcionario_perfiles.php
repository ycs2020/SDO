<?php

header('Content-Type: application/json; charset=utf-8');
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$conexion->set_charset("utf8mb4");


$idCredencial = isset($_POST['idCredencialPerfil']) ? (int)$_POST['idCredencialPerfil'] : 0;

if ($idCredencial <= 0) {
    echo json_encode([]);
    exit;
}

$sql = $conexion->prepare("
    SELECT 
        idPerfil,
        nomPerfil,
        descripPerfil
    FROM protoc_ccaa_perfil
    ORDER BY nomPerfil ASC
");

$sql->execute();
$resultado = $sql->get_result();

$datos = [];
while ($fila = $resultado->fetch_assoc()) {
    $idPerfil = $fila['idPerfil'];
    $nomPerfil = $fila['nomPerfil'];
    $descripPerfil = $fila['descripPerfil'];

    $nomPerfilSeguro = htmlspecialchars($nomPerfil, ENT_QUOTES, 'UTF-8');

    $perfilActivado = "
                        <button 
                            class='btn btn-sm btn-primary btn-block btnRegistroPerfil'
                            data-perfil='$idPerfil'
                            data-nomperfil=\"$nomPerfilSeguro\"
                            data-credencial='$idCredencial'
                            data-accion='Autoriza'>
                            <i class='fas fa-check'></i>&nbsp;&nbsp;Autorizar
                        </button>";

    $res = getExistePerfilCredencial($idPerfil, $idCredencial);
    if (isset($res['total']) && $res['total'] > 0) {

        $perfilActivado = "
                            <button 
                                class='btn btn-sm btn-danger btn-block btnRegistroPerfil'
                                data-perfil='$idPerfil'
                                data-nomperfil=\"$nomPerfilSeguro\"
                                data-credencial='$idCredencial'
                                data-accion='Anular'>
                                <i class='fas fa-times'></i>&nbsp;&nbsp;Anular
                            </button>";
    }

    $nomPerfil2 = mb_convert_case($nomPerfil, MB_CASE_UPPER, 'UTF-8');

    $fila['nomPerfil2'] = $nomPerfil2;
    $fila['perfilActivado'] = $perfilActivado;

    $datos[] = $fila; // 👈 esta es la forma correcta
}

echo json_encode($datos, JSON_UNESCAPED_UNICODE); // importante para emojis, tildes, etc.
$sql->close();
$conexion->close();
