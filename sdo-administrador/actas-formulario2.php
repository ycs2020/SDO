<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Actas</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>

<body class="hold-transition sidebar-mini ">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Formulario de Actas</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="../sdo-funciones/actas-generar.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">

                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Formulario de Actas</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <?php


                                    if (isset($_GET['errorCrearActa'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                    }


                                    ?>
                                    <div class="card-body">


                                        <a href="actas-listado.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">T&eacute;cnico de Turno de Control Centralizado:</legend>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">T&eacute;cnico de Turno de Control Centralizado:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>

                                                        <select class="form-control" name="selectTecnicoTurno" id="selectTecnicoTurno">
                                                            <option value="">Seleccione...</option>
                                                            <?php

                                                            $queryTecnicos = "SELECT * FROM personal WHERE departamentoPersonal = " . getIDInstrumentacion() . " ORDER BY nomPersonal ASC";

                                                            $resTecnicos = mysqli_query($conexion, $queryTecnicos);


                                                            while ($rowTecnicos = $resTecnicos->fetch_assoc()) {

                                                            ?>
                                                                <option value="<?php echo $rowTecnicos['nomPersonal']; ?>">
                                                                    <?php echo $rowTecnicos['nomPersonal']; ?>
                                                                </option>
                                                            <?php

                                                            }


                                                            ?>
                                                        </select>


                                                    </div>
                                                </div>

                                            </div>

                                        </fieldset>

                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n del Funcionario:</legend>


                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Nombre de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="nomTarjeta" name="nomTarjeta" placeholder="Nombre y Apellido de Usuario de Tarjeta">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">RUT de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="rutTarjeta" name="rutTarjeta" placeholder="RUT de Usuario de Tarjeta (Formato XXXXXXXXX, la K es 0)">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Calidad Contractual de Funcionario:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>

                                                        <select class="form-control" name="calidadContractual" id="calidadContractual">
                                                            <option value="Seleccione">Seleccione...</option>
                                                            <option value="Contrata">Alumno en Practica</option>
                                                            <option value="Contrata">Contrata</option>
                                                            <option value="Empresa Externa">Empresa Externa</option>
                                                            <option value="Honorarios">Honorarios</option>
                                                            <option value="Reemplazo">Reemplazo</option>
                                                            <option value="Titular">Titular</option>
                                                            <option value="Clinico Interno">Clinico Interno</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">N&uacute;mero de Contacto:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                        </div>
                                                        <input type="text" required class="form-control" id="numTelefono" name="numTelefono" placeholder="+569XXXXXXXX">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Funcionario con Beca:&nbsp;</legend>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">

                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="chkBecado" name="chkBecado" value="Becado">
                                                        <label id="" for="chkBecado" class="custom-control-label">Persona Becada</label>
                                                        <input type="hidden" name="valorBeca" id="valorBeca" value="NO">
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('chkBecado').onchange = function() {
                                                            var esBecado = document.getElementById('valorBeca').value;

                                                            if (esBecado == 'SI') {
                                                                document.getElementById('valorBeca').value = 'NO';
                                                            } else if (esBecado == 'NO') {
                                                                document.getElementById('valorBeca').value = 'SI';
                                                            } else {
                                                                document.getElementById('valorBeca').value = 'NO';
                                                            }
                                                        };
                                                    </script>
                                                </div>
                                            </div>


                                        </fieldset>

                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n de la Tarjeta:</legend>

                                            <div class="form-row">


                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Codigo de Tarjeta:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase" class="form-control" id="codigoTarjeta" name="codigoTarjeta" placeholder="Codigo de Tarjeta">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputUsuarioTarjeta">Unidad del Usuario de Tarjeta:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                        </div>
                                                        <input type="text" required class="form-control" id="unidadTarjeta" name="unidadTarjeta" placeholder="Unidad del Usuario de Tarjeta">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInput">Nombre de Quien Solicita:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="nomSolicitante" name="nomSolicitante" placeholder="Nombre de Quien Solicita">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n sobre Parking:</legend>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">

                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="checkBoxDiscapacidad" name="checkBoxDiscapacidad" value="Discapacitado">
                                                        <label id="" for="checkBoxDiscapacidad" class="custom-control-label">Persona con Discapacidad</label>
                                                        <input type="hidden" name="valorDiscapacidad" id="valorDiscapacidad" value="NO">
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('checkBoxDiscapacidad').onchange = function() {
                                                            var esDiscapacitado = document.getElementById('valorDiscapacidad').value;

                                                            if (esDiscapacitado == 'SI') {
                                                                document.getElementById('valorDiscapacidad').value = 'NO';
                                                            } else if (esDiscapacitado == 'NO') {
                                                                document.getElementById('valorDiscapacidad').value = 'SI';
                                                            } else {
                                                                document.getElementById('valorDiscapacidad').value = 'NO';
                                                            }
                                                        };
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="checkboxParking" name="checkboxParking" value="option1">
                                                        <label for="checkboxParking" class="custom-control-label">Necesita Permisos de Estacionamiento</label>
                                                        <input type="hidden" name="valorEstacionamiento" id="valorEstacionamiento" value="NO">
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('checkboxParking').onchange = function() {
                                                            document.getElementById('patente1').disabled = !this.checked;
                                                            document.getElementById('patente2').disabled = !this.checked;
                                                            document.getElementById('modelo1').disabled = !this.checked;
                                                            document.getElementById('modelo2').disabled = !this.checked;

                                                            var valorEstacionamiento = document.getElementById('valorEstacionamiento').value;

                                                            if (valorEstacionamiento == 'SI') {
                                                                document.getElementById('valorEstacionamiento').value = 'NO';
                                                                document.getElementById('patente1').style.backgroundColor = 'gray';
                                                                document.getElementById('patente2').style.backgroundColor = 'gray';
                                                                document.getElementById('modelo1').style.backgroundColor = 'gray';
                                                                document.getElementById('modelo2').style.backgroundColor = 'gray';
                                                            } else if (valorEstacionamiento == 'NO') {
                                                                document.getElementById('valorEstacionamiento').value = 'SI';
                                                                document.getElementById('patente1').style.backgroundColor = 'transparent';
                                                                document.getElementById('patente2').style.backgroundColor = 'transparent';
                                                                document.getElementById('modelo1').style.backgroundColor = 'transparent';
                                                                document.getElementById('modelo2').style.backgroundColor = 'transparent';
                                                            } else {
                                                                document.getElementById('valorEstacionamiento').value = 'NO';
                                                                document.getElementById('patente1').style.backgroundColor = 'gray';
                                                                document.getElementById('patente2').style.backgroundColor = 'gray';
                                                                document.getElementById('modelo1').style.backgroundColor = 'gray';
                                                                document.getElementById('modelo2').style.backgroundColor = 'gray';
                                                            }
                                                        };
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputUsuarioTarjeta">Patente de Vehiculo N° 1:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="background-color: gray; text-transform:uppercase;" class="form-control" id="patente1" name="patente1" placeholder="Patente de Vehiculo N° 1: (XXXXXX)">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Modelo y Color de Vehiculo N° 1</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="background-color: gray; text-transform:capitalize" class="form-control" id="modelo1" name="modelo1" placeholder="Modelo y Color de Vehiculo N° 1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputUsuarioTarjeta">Patente de Vehiculo N° 2</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="background-color: gray; text-transform:uppercase;" class="form-control" id="patente2" name="patente2" placeholder="Unidad del Usuario de Tarjeta">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Modelo y Color de Vehiculo N° 2</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="background-color: gray; text-transform:capitalize" class="form-control" id="modelo2" name="modelo2" placeholder="Modelo y Color de Vehiculo N° 2">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <br>
                                        <br>


                                        <div class="form-group">
                                            <input type="hidden" name="idTecnico" id="idTecnico" value="<?php echo $idUsuario; ?>">
                                            <input type="hidden" id="nomTecnicoTurno" name="nomTecnicoTurno" value="" />
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                        </div>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                            <!--/.col  -->

                        </div>
                    </form>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
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
    <!-- DataTables  & Plugins -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $('#selectTecnicoTurno').change(function() {
            var id = $(this).find(':selected')[0].value;
            $('#nomTecnicoTurno').val(id);
        })
    </script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>
    <script src="js/dark-mode.js"></script>
</body>

</html>