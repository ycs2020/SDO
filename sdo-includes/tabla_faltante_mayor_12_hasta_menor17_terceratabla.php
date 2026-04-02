<?php
$segundaCantidadFaltante = $cantidadFaltante - 12;

$largoTabla = 4 + $segundaCantidadFaltante;
$resto = 100 - 40;
$dividor = $resto / $largoTabla;
?>
<div style="page-break-before: always">
    <table class="usuarios" style="vertical-align:middle; font-size: 13px; text-align:left; width: 100%; border-collapse: collapse;">

        <thead>
        <tr>
            <td colspan="<?php echo $largoTabla; ?>" style=" width: 100%; text-align: center; background-color: lightgrey;">

                <b>ACCESOS A UNIDAD: <?php echo $buscarNomUnidad; ?></b>

            </td>

        </tr>
        <tr>

            <td style=" width: <?php echo $dividor; ?>%; text-align: center; background-color: lightgrey;">

                <b>NOMBRE:</b>

            </td>

            <td style=" width: <?php echo $dividor; ?>%; text-align: center; background-color: lightgrey;">

                <b>RUT:</b>

            </td>

            <td style=" width: <?php echo $dividor; ?>%; text-align: center; background-color: lightgrey;">

                <b>CALIDAD JURIDICA:</b>

            </td>

            <td style=" width: <?php echo $dividor; ?>%; text-align: center; background-color: lightgrey;">

                <b>UNIDAD:</b>

            </td>

            <?php


            for ($i = 17; $i <= $cantidadFormCCAA; $i++) {
                ?>

                <td style=" width: <?php echo $dividor; ?>%; text-align: center; background-color: lightgrey;">

                    <b><?php echo $rowAccesos['door' . $i . 'FormCCAA']; ?></b>

                </td>
                <?php
            }

            ?>

        </tr>
        </thead>


        <?php

        $sqlBuscarFuncionarios = "SELECT * FROM formularios_funcionarios_prueba WHERE idFormRegAcceso =  $idFormRegAcceso AND idUnidad = $unidadFuncionario";

        $resBuscarFuncionarios = mysqli_query($conexion, $sqlBuscarFuncionarios);

        while ($rowBuscarFuncionarios = $resBuscarFuncionarios->fetch_assoc()) {
            ?>
            <tr>

                <td style=" width: <?php echo $dividor; ?>%; text-align: center;">
                    <?php
                    $nomFormFuncCCAA = $rowBuscarFuncionarios['nomFormFuncCCAA'];

                    $nomFormFuncCCAA2 = mb_convert_case($nomFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                    echo $nomFormFuncCCAA2;
                    ?>
                </td>

                <td style=" width: <?php echo $dividor; ?>%; text-align: center;">
                    <?php
                    $rutFormFuncCCAA = $rowBuscarFuncionarios['rutFormFuncCCAA'];

                    $rutFormFuncCCAA2 = mb_convert_case($rutFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                    echo $rutFormFuncCCAA2;
                    ?>
                </td>

                <td style=" width: <?php echo $dividor; ?>%; text-align: center;">
                    <?php
                    $calidadFormFuncCCAA = $rowBuscarFuncionarios['calidadFormFuncCCAA'];

                    $calidadFormFuncCCAA2 = mb_convert_case($calidadFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                    echo $calidadFormFuncCCAA2;
                    ?>
                </td>

                <td style=" width: <?php echo $dividor; ?>%; text-align: center;">
                    <?php
                    $unidadFormFuncCCAA = $rowBuscarFuncionarios['unidadFormFuncCCAA'];

                    $unidadFormFuncCCAA2 = mb_convert_case($unidadFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                    echo $unidadFormFuncCCAA2;
                    ?>
                </td>

                <?php


                for ($i = 17; $i <= $cantidadFormCCAA; $i++) {
                    ?>

                    <td style=" width: <?php echo $dividor; ?>%; text-align: center;">

                        <b>
                            <?php

                            $opcion = $rowBuscarFuncionarios['door' . $i . 'FormCCAA'];

                            if ($opcion == 1) {
                                ?>
                                <p><i class="fas fa-check"></i></p>
                                <?php
                            }
                            ?>
                        </b>

                    </td>
                    <?php
                }

                ?>

            </tr>
            <?php
        }

        ?>
    </table>
</div>
