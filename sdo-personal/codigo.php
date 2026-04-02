<?php
include '../lic-funciones/conexion.php';
include '../lic-funciones/funciones.php';

if (isset($_POST['idLicitacion'], $_POST['LicitacionVisita'])) {
    $idLicitacion = trim($_POST['idLicitacion']);
    $LicitacionVisita = trim($_POST['LicitacionVisita']);

    if ($idLicitacion == " " || $idLicitacion == "" || $LicitacionVisita == " " || $LicitacionVisita == "") {
        header('Location: ../lic-admin/index.php?errorCargarDetalle=true');
    } else {
        $datos = array();
        $datos = getLicitacionFecha($idLicitacion);

        $nomLicitacion = $datos['nomLicitacion'];

        $queryRevisor = 'SELECT COUNT(*) as total FROM videos WHERE idLicitacion = ' . $idLicitacion . '';
        $resRevisor = mysqli_query($conexion, $queryRevisor);
        $datoRevisor = mysqli_fetch_assoc($resRevisor);

        $quedaVideos = 6 - $datoRevisor['total'];
    }
} else if (isset($_GET['idLicitacion'], $_GET['LicitacionVisita'])) {
    $idLicitacion = trim($_GET['idLicitacion']);
    $LicitacionVisita = trim($_GET['LicitacionVisita']);

    if ($idLicitacion == " " || $idLicitacion == "" || $LicitacionVisita == " " || $LicitacionVisita == "") {
        header('Location: ../lic-admin/index.php?errorCargarDetalle=true');
    } else {
        $datos = array();
        $datos = getLicitacionFecha($idLicitacion);

        $nomLicitacion = $datos['nomLicitacion'];

        $queryRevisor = 'SELECT COUNT(*) as total FROM video WHERE idVisita = ' . $idVisita . '';
        $resRevisor = mysqli_query($conexion, $queryRevisor);
        $datoRevisor = mysqli_fetch_assoc($resRevisor);

        $quedaVideos = 6 - $datoRevisor['total'];
    }
} else {

    header('Location: ../lic-admin/index.php?errorCargarDetalle=true');
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Videos de Licitaci&oacute;n</title>
    <script src="../plugins/jquery/jquery.js"></script>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="jquery-3.5.1.min.js"></script>

    <style>
        .color-palette {
            height: 35px;
            line-height: 35px;
            text-align: right;
            padding-right: .75rem;
        }

        .color-palette.disabled {
            text-align: center;
            padding-right: 0;
            display: block;
        }

        .color-palette-set {
            margin-bottom: 15px;
        }

        .color-palette span {
            display: none;
            font-size: 12px;
        }

        .color-palette:hover span {
            display: block;
        }

        .color-palette.disabled span {
            display: block;
            text-align: left;
            padding-left: .75rem;
        }

        .color-palette-box h4 {
            position: absolute;
            left: 1.25rem;
            margin-top: .75rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            display: block;
            z-index: 7;
        }
    </style>


</head>

<body class="hold-transition sidebar-mini dark-mode">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        include '../lic-templates/admin-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">


                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <?php
                            if ($LicitacionVisita == "Licitacion") {
                            ?>
                                <h1>Agregar Videos a Licitación Virtual</h1>
                            <?php
                            } else if ($LicitacionVisita == "Visita") {
                            ?>
                                <h1>Agregar Videos a Visita Virtual</h1>
                            <?php
                            } else {
                                header('Location: ../lic-admin/index.php?errorAgregarVideos=true');
                            }
                            ?>

                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../lic-admin/index.php">Home</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                                    <div id="accordion">

                                        <div class="card">
                                            <div class="card-header bg-primary">
                                                <h4 class="card-title">
                                                    <a class="d-block w-100" data-toggle="collapse" href="#collapse">
                                                        <h5 style="color:white;"><b style="color:white;"><?php echo 'LICITACI&Oacute;N: ' . $nomLicitacion; ?></b></h5>
                                                    </a>

                                                </h4>
                                            </div>

                                            <div id="collapse" class="collapse show" data-parent="#accordion">
                                                <br>
                                                <section class="content">
                                                    <div class="container-fluid">

                                                        <div class="row">
                                                            <div class="col-12">

                                                                <br>
                                                                <h6 id="limiteMax"><b>Importante: </b>Límite máximo: 6 videos. Se pueden subir: <?php echo $quedaVideos . ' videos m&aacute;s'; ?></h6>
                                                                <br>
                                                                <div>
                                                                    <form method="post" action="../lic-funciones/videos/agregarVideo.php">

                                                                        <div class="form-group fieldGroup">



                                                                            <?php
                                                                            if ($quedaVideos == '0') {
                                                                            ?>
                                                                                <hr>
                                                                                <label>Nombre de Video:</label>
                                                                                <input type="text" name="nomVideo[]" class="form-control" required placeholder="Ingrese Nombre del Video" /><br>
                                                                                <label>Descripci&oacute;n del Video:</label>
                                                                                <input type="text" name="descripcionVideo[]" class="form-control" required placeholder="Ingrese Descripci&oacute;n de Video" /><br>
                                                                                <label>Enlace Video:</label>
                                                                                <input type="text" name="enlaceVideo[]" class="form-control" required placeholder="Ingrese Enlace de Video" />
                                                                                <br>
                                                                                <div class="form-row justify-content-center align-items-center">
                                                                                    <div class="form-group col-md-4 ">
                                                                                        <button disabled class="btn btn-block btn-success">Agregar Otro Video</button>
                                                                                    </div>
                                                                                </div>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <hr>
                                                                                <label>Nombre de Video:</label>
                                                                                <input type="text" name="nomVideo[]" class="form-control" required placeholder="Ingrese Nombre del Video" /><br>
                                                                                <label>Descripci&oacute;n del Video:</label>
                                                                                <input type="text" name="descripcionVideo[]" class="form-control" required placeholder="Ingrese Descripci&oacute;n de Video" /><br>
                                                                                <label>Enlace Video:</label>
                                                                                <input type="text" name="enlaceVideo[]" class="form-control" required placeholder="Ingrese Enlace de Video" />
                                                                                <br>
                                                                                <div class="form-row justify-content-center align-items-center">
                                                                                    <div class="form-group col-md-4 ">
                                                                                        <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Agregar Otro Video</a>

                                                                                    </div>
                                                                                </div>
                                                                                <hr>
                                                                            <?php
                                                                            }
                                                                            ?>

                                                                        </div>

                                                                        <br>
                                                                        <br>
                                                                        <br>
                                                                        <br>

                                                                        <div class="form-row justify-content-center align-items-center">
                                                                            <div class="form-group col-md-4 ">
                                                                                <?php
                                                                                if ($LicitacionVisita == "Licitacion") {
                                                                                ?>
                                                                                    <input type="hidden" name="LicitacionVisita" value="<?php echo $LicitacionVisita; ?>">
                                                                                    <input type="hidden" name="idLicitacion" value="<?php echo $idLicitacion; ?>">
                                                                                    <input type="hidden" name="idVisita" value="0">
                                                                                    <?php
                                                                                    if ($quedaVideos == "0") {
                                                                                    ?>
                                                                                        <input type="submit" class="btn btn-block btn-primary" disabled value="Guardar" />
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                                                                    <?php
                                                                                    }
                                                                                    ?>

                                                                                    <br>
                                                                                    <a href="../lic-admin/index.php" class="btn btn-block btn-warning"> Volver </a>
                                                                                <?php
                                                                                } else if ($LicitacionVisita == "Visita") {
                                                                                ?>
                                                                                    <input type="hidden" name="LicitacionVisita" value="<?php echo $LicitacionVisita; ?>">
                                                                                    <input type="hidden" name="idLicitacion" value="0">
                                                                                    <input type="hidden" name="idVisita" value="<?php echo $idVisita; ?>">
                                                                                    <?php
                                                                                    if ($quedaVideos == "0") {
                                                                                    ?>
                                                                                        <input type="submit" class="btn btn-block btn-primary" disabled value="Guardar" />
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                    <br>
                                                                                    <a href="../lic-admin/index3.php" class="btn btn-block btn-warning"> Volver </a>
                                                                                <?php
                                                                                } else {
                                                                                    header('Location: ../lic-admin/index.php?errorAgregarVideos=true');
                                                                                }
                                                                                ?>

                                                                            </div>
                                                                        </div>

                                                                    </form>

                                                                    <!-- copy of input fields group -->
                                                                    <div class="form-group fieldGroupCopy" style="display: none;">

                                                                        <hr>
                                                                        <label>Nombre de Video:</label>
                                                                        <input type="text" name="nomVideo[]" class="form-control" required placeholder="Ingrese Nombre del Video" /><br>
                                                                        <label>Descripci&oacute;n del Video:</label>
                                                                        <input type="text" name="descripcionVideo[]" class="form-control" required placeholder="Ingrese Descripci&oacute;n de Video" /><br>
                                                                        <label>Enlace Video:</label>
                                                                        <input type="text" name="enlaceVideo[]" class="form-control" required placeholder="Ingrese Enlace de Video" />
                                                                        <br>
                                                                        <div class="form-row justify-content-center align-items-center">
                                                                            <div class="form-group col-md-4 ">
                                                                                <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                                                                            </div>
                                                                        </div>
                                                                        <hr>


                                                                    </div>
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function() {
                                                                            //group add limit
                                                                            var maxGroup = "<?php echo $quedaVideos ?>";

                                                                            //add more fields group
                                                                            $(".addMore").click(function() {
                                                                                if ($('body').find('.fieldGroup').length < maxGroup) {
                                                                                    var fieldHTML = '<div class="form-group fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                                                                    $('body').find('.fieldGroup:last').after(fieldHTML);

                                                                                } else {
                                                                                    alert('Limite de Videos Completado. Solo se permiten ' + maxGroup + ' videos.');
                                                                                }
                                                                            });

                                                                            //remove fields group
                                                                            $("body").on("click", ".remove", function() {
                                                                                $(this).parents(".fieldGroup").remove();
                                                                            });
                                                                        });
                                                                    </script>


                                                                </div>


                                                            </div>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </section>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->



    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>

</body>


</html>



<script type="text/javascript">
    function mayus(e) {
        e.value = e.value.toUpperCase();
    }
</script>