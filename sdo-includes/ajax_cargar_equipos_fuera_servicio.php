<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (isset($_POST['idCategoria']) && is_numeric($_POST['idCategoria'])) {
    $idCategoria = (int)$_POST['idCategoria'];

    if ($idCategoria <= 0) {
        echo "<div class='alert alert-danger'>ID de categoría inválido.</div>";
        exit;
    }

    $sql = "SELECT id FROM equipamiento_hbqp WHERE idCategoria = $idCategoria AND estadoEquipo LIKE 'Fuera de Servicio' AND idEquipoPadre > -1 ORDER BY nombreEquipo ASC;";
    $result = mysqli_query($conexion, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-warning'>No se encontraron datos para esta categoría.</div>";
        exit;
    }

    $numFueraServicio = 1;
    ?>

    <style>
        #tablaEquiposFueraServicio td,
        #tablaEquiposFueraServicio th {
            padding-top: 0.35rem !important;
            padding-bottom: 0.35rem !important;
            vertical-align: middle !important;
        }
    </style>

    <div class="table-responsive">
        <table class="table table-bordered table-sm" id="tablaEquiposFueraServicio" style="width: 100%; font-size: 10pt;">
            <thead class="bg-danger text-white">
            <tr>
                <th scope="col" class="text-center align-middle">N°</th>
                <th scope="col" class="text-center align-middle">Usuario Observador</th>
                <th scope="col" class="text-center align-middle">Nombre del Equipo</th>
                <th scope="col" class="text-center align-middle">Equipo</th>
                <th scope="col" class="text-center align-middle">Fecha</th>
                <th scope="col" class="text-center align-middle">Tipo</th>
                <th scope="col" class="text-center align-middle">Detalle</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                $idEquipo = $row['id'];

                $sqlHistorial = "
                    SELECT * 
                    FROM historial_cambios_equipos 
                    WHERE idEquipo = $idEquipo 
                      AND estado LIKE '%Fuera de Servicio%' 
                    ORDER BY fecha DESC 
                    LIMIT 1";
                $resHistorial = mysqli_query($conexion, $sqlHistorial);

                if ($resHistorial && mysqli_num_rows($resHistorial) > 0) {
                    $historial = $resHistorial->fetch_assoc();

                    $fechaFS = date('d/m/Y', strtotime($historial['fecha']));
                    $estadoFS = mb_convert_case($historial['estado'], MB_CASE_UPPER, 'UTF-8');
                    $observacionesFS = $historial['observaciones'];
                    $observacionesFS2 = mb_convert_case($observacionesFS, MB_CASE_UPPER, 'UTF-8');
                    $nombreEquipoFS = $historial['nombreEquipo'];
                    $idUsuario = $historial['idUsuarioCambia'];
                    $tipoUsuario = $historial['tipoUsuario'];
                    $idEquipoFS = $historial['idEquipo'];

                    $tablaUsuarios = [
                        'administrativo' => ['adm_tecnicos', 'nomAdmTecnicos', 'idAdmTecnicos'],
                        'jefe tecnico'   => ['jefes_tecnicos', 'nomJefe', 'idJefe'],
                        'personal'       => ['personal', 'nomPersonal', 'idPersonal'],
                        'administrador'  => ['administracion', 'nomAdministrador', 'idAdministrador'],
                        'mesa ayuda'     => ['mesa_ayuda', 'nomMesaAyuda', 'idMesaAyuda'],
                        'directiva'      => ['directivos', 'nomDirectivo', 'idDirectivo']
                    ];

                    $nomUsuarioFS = 'NO APLICA.';
                    if (isset($tablaUsuarios[$tipoUsuario])) {
                        [$tabla, $campoNombre, $campoID] = $tablaUsuarios[$tipoUsuario];
                        $sqlUser = "SELECT $campoNombre AS nombre FROM $tabla WHERE $campoID = $idUsuario LIMIT 1";
                        $resUser = mysqli_query($conexion, $sqlUser);
                        if ($resUser && $rowUser = $resUser->fetch_assoc()) {
                            $nomUsuarioFS = $rowUser['nombre'] ?: 'NO APLICA.';
                        }
                    }

                    $nomUsuarioFS2 = mb_convert_case($nomUsuarioFS, MB_CASE_UPPER, 'UTF-8');
                    $tipoMenuFS = obtenerTipoMenu($idEquipoFS);
                    $rutaMenuFS = ($tipoMenuFS == 'nuevo') ? 'equipo_menuNuevo.php' : 'equipo_menuAntiguo.php';
                    ?>
                    <tr>
                        <td class="text-center align-middle"><?= $numFueraServicio ?></td>
                        <td class="text-center align-middle"><?= $nomUsuarioFS2 ?></td>
                        <td class="text-center align-middle"><?= $nombreEquipoFS ?></td>
                        <td class="text-center align-middle">
                            <form method="POST" action="<?= $rutaMenuFS ?>">
                                <input type="hidden" name="idEquipo" value="<?= $idEquipoFS ?>">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </form>
                        </td>
                        <td class="text-center align-middle"><?= $fechaFS ?></td>
                        <td class="text-center align-middle"><?= $estadoFS ?></td>
                        <td class="text-center align-middle"><?= $observacionesFS2 ?></td>
                    </tr>
                    <?php
                }

                $numFueraServicio++;
            }
            ?>
            </tbody>
            <tfoot class="bg-danger text-white">
            <tr>
                <th scope="col" class="text-center align-middle">N°</th>
                <th scope="col" class="text-center align-middle">Usuario Observador</th>
                <th scope="col" class="text-center align-middle">Nombre del Equipo</th>
                <th scope="col" class="text-center align-middle">Equipo</th>
                <th scope="col" class="text-center align-middle">Fecha</th>
                <th scope="col" class="text-center align-middle">Tipo</th>
                <th scope="col" class="text-center align-middle">Detalle</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <?php

} else {
    echo "<div class='alert alert-danger'>ID de categoría inválido o no enviado.</div>";
}
?>
