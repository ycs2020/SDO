<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';


/**
 * Ejecuta una consulta SQL y devuelve el valor del campo 'totalEquipos'
 */
function obtenerTotalEquipos($sql)
{
    include '../sdo-funciones/conexion.php';

    $res = mysqli_query($conexion, $sql);

    if ($row = mysqli_fetch_assoc($res)) {
        return (int)$row['totalEquipos'];
    }

    return 0;
}

$ipCliente = $_SERVER['REMOTE_ADDR'];

if (isset($_POST['idDepartamento'])) {
    $id = (int)$_POST['idDepartamento'];
    $idDeptoPrincipal = (int)$_POST['idDeptoPrincipal'];


    if ($id > 0) {
        $sql = "SELECT * FROM categorias_equipos WHERE idDepartamento = $id ORDER BY nomCategoria ASC";
        $res = mysqli_query($conexion, $sql);

        $categoriasExcluidas = ['CONTROL CENTRALIZADO', 'SISTEMA INCENDIO', 'BITACORA', 'BITÁCORA'];

        if ($res && mysqli_num_rows($res) > 0) {
            ?>
            <div class="row justify-content-center">
                <?php
                $totalValorDepartamento = moneda_chilena(obtenerValorCompletoSistemaDepto($id));
                ?>
                <div class="text-center mb-4">
    <span class="badge bg-purple p-3 shadow-sm text-md font-weight-bold" style="border-radius: 30px; letter-spacing: 0.5px;">
        <i class="fas fa-wallet mr-2"></i> Valor Total de Sistemas: $ 1.997.435.149
    </span>
                </div>

                <?php
                if (in_array($idDeptoPrincipal, [1, 13]) && $ipCliente == '10.69.250.151' ) {
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                        <button
                                type="button"
                                class="btn btn-primary btn-block"
                                id="btnDescargarInformeEquipos<?php echo $id; ?>"
                                data-ideq="<?php echo $id; ?>"
                        >
                            <i class="fas fa-download"></i>&nbsp;&nbsp;Descargar Informe de Equipos
                        </button>

                        <script>
                            $('#btnDescargarInformeEquipos<?php echo $id; ?>').on('click', function () {
                                var id = $(this).data('ideq');

                                var form = $('<form>', {
                                    'action': '../sdo-pdf/SDO-Informe-Operatividad-Equipos.php',
                                    'method': 'POST',
                                    'target': '_blank'
                                });

                                form.append($('<input>', {
                                    'type': 'hidden',
                                    'name': 'idDeptoInforme',
                                    'value': id
                                }));

                                form.appendTo('body').submit();
                            });
                        </script>
                    </div>
                    <?php
                }
                ?>
            </div>

            <hr>

            <div class="contenedor-categorias-departamento">
                <div class="row mb-3">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input
                                    type="text"
                                    class="form-control buscador-categoria"
                                    placeholder="Buscar categoría..."
                                    autocomplete="off"
                            >
                        </div>
                        <small class="text-muted">
                            Escribe el nombre de una categoría para filtrar los sistemas.
                        </small>
                    </div>
                </div>

                <div class="row lista-categorias">
                    <?php
                    while ($row = mysqli_fetch_assoc($res)) {
                        $idCategoria = $row['idCategoria'];
                        $nomCategoria = $row['nomCategoria'];
                        $imagenCategoria = $row['imagenCategoria'];

                        $registrados = contarequiposcategoria($idCategoria);
                        $operativos = contareuiposoperativos($idCategoria);
                        $observaciones = contarequiposobs($idCategoria);
                        $mantenimiento = contarequiposmanteni($idCategoria);
                        $fuera = contarequiposfallas($idCategoria);
                        $valor = obtenerValorCompletoSistema($idCategoria);

                        $nomCategoriaNormalizado = mb_convert_case($nomCategoria, MB_CASE_UPPER, 'UTF-8');

                        if (!in_array($nomCategoriaNormalizado, $categoriasExcluidas)) {
                            $urlCarpeta = "carpeta-equipo.php";
                            ?>
                            <div
                                    class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3 categoria-item"
                                    data-categoria="<?php echo htmlspecialchars($nomCategoriaNormalizado, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                                <div class="card card-primary card-outline border-primary mb-3">
                                    <div class="card-header text-center">
                                        <img
                                                src="<?php echo $imagenCategoria; ?>"
                                                class="img-fluid"
                                                alt="Imagen"
                                                style="height: 180px;"
                                        >
                                        <br>&nbsp;<br>

                                        <h6 class="mt-2 font-weight-bold">
                                            <?php echo $nomCategoriaNormalizado; ?>
                                        </h6>

                                        <hr>

                                        <form action="<?php echo $urlCarpeta; ?>" method="POST">
                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCategoria; ?>">
                                            <button type="submit" class="btn bg-teal btn-block">
                                                <b><i class="fas fa-eye"></i>&nbsp;&nbsp;Ingresar a Sistema</b>
                                            </button>
                                        </form>

                                        <?php
                                        if ($ipCliente == '10.69.250.151') {
                                            ?>
                                            <hr>

                                            <form action="equipamiento_hbqp_dashboard.php" target="_blank" method="POST">
                                                <input type="hidden" name="idCarpeta" value="<?php echo $idCategoria; ?>">
                                                <button type="submit" class="btn bg-info btn-block">
                                                    <b><i class="fas fa-chart-bar"></i>&nbsp;&nbsp;Ver Dashboard</b>
                                                </button>

                                            </form>

                                            <?php
                                        }
                                        ?>

                                        <hr>

                                        <div
                                                class="btn bg-purple btn-block mb-1 btn-valor-sistema"
                                                data-toggle="modal"
                                                data-target="#modalValorSistema"
                                                data-id="<?php echo $idCategoria; ?>"
                                        >
                                            <i class="fas fa-money-bill-wave"></i>
                                            Valor Sistema:
                                            <b>$ <?php echo number_format($valor, 0, ',', '.'); ?></b>
                                        </div>

                                        <hr>

                                        <div class="btn btn-primary btn-block mb-1">
                                            <i class="fas fa-desktop"></i>
                                            Equipos Registrados:
                                            <b><?php echo $registrados; ?></b>
                                        </div>

                                        <div class="btn btn-success btn-block mb-1">
                                            <i class="fas fa-check"></i>
                                            Equipos Operativos:
                                            <b><?php echo $operativos; ?></b>
                                        </div>

                                        <div class="btn btn-secondary btn-block mb-1">
                                            <i class="fas fa-cogs"></i>
                                            Equipos Con Observaciones:
                                            <b><?php echo $observaciones; ?></b>
                                        </div>

                                        <div class="btn btn-warning btn-block mb-1">
                                            <i class="fas fa-wrench"></i>
                                            Equipos en Mantenimiento:
                                            <b><?php echo $mantenimiento; ?></b>
                                        </div>

                                        <div
                                                class="btn btn-danger btn-block btn-fuera-servicio"
                                                data-toggle="modal"
                                                data-target="#modalFueraServicio"
                                                data-id="<?php echo $idCategoria; ?>"
                                        >
                                            <i class="fas fa-skull-crossbones"></i>
                                            Equipos Fuera de Servicio:
                                            <b><?php echo $fuera; ?></b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning text-center mensaje-sin-resultados" style="display: none;">
                            No se encontraron categorías con ese nombre.
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo "<div class='alert alert-info'>No hay sistemas registrados para este departamento.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error al Mostrar Elementos.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Error al Mostrar Elementos.</div>";
}
?>