<?php
include_once '../sdo-funciones/conexion.php';

$idGrupoVer = isset($_POST['idGrupoVer']) ? (int)$_POST['idGrupoVer'] : 0;

if ($idGrupoVer <= 0) {
    echo "<p class='text-muted'>Grupo inválido.</p>";
    exit;
}

$sql = "
  SELECT
    gp.idPerfilGrupo,
    UPPER(p.nomPerfil) AS nomPerfil,
    p.descripPerfil,
    DATE_FORMAT(gp.fechaPerfilGrupo, '%d-%m-%Y %H:%i') AS fechaRegistro
  FROM protoc_ccaa_perfil_grupo gp
  INNER JOIN protoc_ccaa_perfil p ON p.idPerfil = gp.idPerfil
  WHERE gp.idGrupo = $idGrupoVer
  ORDER BY p.nomPerfil ASC
";

$res = mysqli_query($conexion, $sql);

if ($res && mysqli_num_rows($res) > 0) {

    echo "<table id='tablaPerfilesGrupo' class='table table-bordered table-hover table-sm w-100'>
            <thead class='bg-success text-white text-center'>
              <tr>
                <th style='width:10%;'>N°</th>
                <th style='width:35%;'>Perfil</th>
                <th style='width:55%;'>Descripción</th>
              </tr>
            </thead>
            <tbody>";

    $n = 1;
    while ($row = mysqli_fetch_assoc($res)) {

        $perfil = htmlspecialchars($row['nomPerfil'], ENT_QUOTES, 'UTF-8');
        $desc   = htmlspecialchars($row['descripPerfil'], ENT_QUOTES, 'UTF-8');

        $perfil2 = mb_strtoupper($perfil, 'UTF-8');
        $desc2 = mb_strtoupper($desc, 'UTF-8');

        echo "<tr>
                <td class='text-center align-middle'>{$n}</td>
                <td class='align-middle'>{$perfil2}</td>
                <td class='align-middle'>{$desc2}</td>
              </tr>";
        $n++;
    }

    echo "  </tbody>
          </table>";

} else {
    echo "<p class='text-muted'>No se encontraron perfiles asociados a este grupo.</p>";
}
?>
