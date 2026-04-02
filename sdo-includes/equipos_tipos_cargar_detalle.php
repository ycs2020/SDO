<?php
include_once '../sdo-funciones/conexion.php';

$nomEquipo = mysqli_real_escape_string($conexion, $_POST['nomEquipo']);


// 1. Obtener permisos del funcionario (0/1)
$sqlEquipoInv = "SELECT equipoInventario,
                marcaInventario,
                modeloInventario,
                serieInventario
            FROM inventario_equipos
            WHERE equipoInventario LIKE '$nomEquipo'";
$resEquipoInv = mysqli_query($conexion, $sqlEquipoInv);

if($resEquipoInv && mysqli_num_rows($resEquipoInv) > 0){
    ?>
    <table class='table table-bordered table-hover table-sm'>
        <thead class='bg-primary text-white'>
        <tr>
            <th style='text-align: center;'>Equipo</th>
            <th style='text-align: center;'>Marca y Modelo</th>
            <th style='text-align: center;'>Serie</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($rowEquipoInv = mysqli_fetch_assoc($resEquipoInv)) {
            $equipoInventario = $rowEquipoInv['equipoInventario'];
            $marcaInventario = $rowEquipoInv['marcaInventario'];
            $modeloInventario = $rowEquipoInv['modeloInventario'];
            $serieInventario = $rowEquipoInv['serieInventario'];

            $serie = trim($serieInventario, " \t\n\r\0\x0B\"'");
            if($serie){
                $serie = htmlspecialchars($serie);
            }else{
                $serie = 'Sin Información';
            }

            ?>
            <tr>
                <td style="vertical-align: middle; text-align: center;"><?php echo $equipoInventario; ?></td>
                <td style="vertical-align: middle; text-align: center;">
                    <?php
                    $marca = trim($marcaInventario, " \t\n\r\0\x0B\"'");
                    $modelo = trim($modeloInventario, " \t\n\r\0\x0B\"'");

                    // Mostrar según lo que esté disponible
                    if ($marca && $modelo) {
                        echo htmlspecialchars($marca) . '<br>' . htmlspecialchars($modelo);
                    } elseif ($marca) {
                        echo htmlspecialchars($marca);
                    } elseif ($modelo) {
                        echo htmlspecialchars($modelo);
                    } else {
                        echo '-'; // o dejar vacío si prefieres: echo '';
                    }
                    ?>
                </td>
                <td style="vertical-align: middle; text-align: center;"><?php echo $serie; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
<?php
}

$listadoEquipos = ($resEquipoInv && mysqli_num_rows($resEquipoInv) > 0) ? mysqli_fetch_assoc($resEquipoInv) : [];


?>
