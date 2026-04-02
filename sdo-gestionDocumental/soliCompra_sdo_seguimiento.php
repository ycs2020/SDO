<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['idSoliCompra'])) {
    $idSoliCompra = trim($_POST['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSolicitud['idSoliCompra'];

        $numSoliCompra = $datosSolicitud['numSoliCompra'];
        $folioSoliCompra = $datosSolicitud['folioSoliCompra'];
        $fechaSoliCompra = $datosSolicitud['fechaSoliCompra'];
        $personalSoliCompra = $datosSolicitud['personalSoliCompra'];
        $unidadSoliCompra = $datosSolicitud['unidadSoliCompra'];
        $emailSoliCompra = $datosSolicitud['emailSoliCompra'];
        $argumentosSoliCompra = $datosSolicitud['argumentosSoliCompra'];
        $adjuntaCotizacion = $datosSolicitud['adjuntaCotizacion'];
        $estadoSoliCompra = $datosSolicitud['estadoSoliCompra'];


        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSolicitud['idSoliCompra'];

        $numSoliCompra = $datosSolicitud['numSoliCompra'];
        $folioSoliCompra = $datosSolicitud['folioSoliCompra'];
        $fechaSoliCompra = $datosSolicitud['fechaSoliCompra'];
        $personalSoliCompra = $datosSolicitud['personalSoliCompra'];
        $unidadSoliCompra = $datosSolicitud['unidadSoliCompra'];
        $emailSoliCompra = $datosSolicitud['emailSoliCompra'];
        $argumentosSoliCompra = $datosSolicitud['argumentosSoliCompra'];
        $adjuntaCotizacion = $datosSolicitud['adjuntaCotizacion'];
        $estadoSoliCompra = $datosSolicitud['estadoSoliCompra'];



        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seguimiento de la solicitud</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/gestdoc-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Seguimiento de la solicitud</h4>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- MENSAJES DE ACCIONES -->
            <?php

            //TRUE
            if (isset($_GET['errorNumSDA'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al Obtener Numero SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoNumInterno'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Número Interno Asignado Correctamente',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorNumInterno'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error Al Asignar Número Interno SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoEntrega'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al cambiar estado de la solicitud "Entregado a la SDA" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorEntrega'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cambiar estado de la solicitud "Entregado a la SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoEditEstado'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito cambio de estado de la SDA" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorEditEstado'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error en el cambio de estado de la SDA',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoEntregaAbastecimiento'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al cambiar el estado de solicitud a "Derivado a Abastecimiento" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorEntregaAbastecimiento'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cambiar el estado de solicitud a "Derivado a Abastecimiento"',
                    })
                </script>
            <?php
            }

            //TRUE
            if (isset($_GET['exitoOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al añadir ordenes de compra" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['errorOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al añadir ordenes de compra"',
                    })
                </script>
            <?php
            }




            //TRUE
            if (isset($_GET['ExitoEditOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al editar ordenes de compra" ',
                    })
                </script>
            <?php
            }

            //FALSE
            if (isset($_GET['ErrorEditOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al editar ordenes de compra"',
                    })
                </script>
            <?php
            }


            ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">

                                    <a href="soliCompra_sdo_administracion.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>


                                </div>
                                <div style="font-size:85%" class="card-body">
                                    <div class="row">
                                        <div style="font-size:85%" class="col-md-9">

                                            <h5 style="font-size:150%"><b>Visualizando Solicitud:
                                                </b><?php echo $folioSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Funcionario Solicitante:
                                                </b><?php echo $personalSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Unidad Solicitante:
                                                </b><?php echo $unidadSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Correo Solicitante:
                                                </b><?php echo $emailSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Estado Solicitud:
                                                </b><?php echo $estadoSoliCompra; ?></h5>

                                            <br>
                                        </div>



                                    </div>
                                    <br>


                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4>¿Como va mi solicitud?</h4>
                                    <br>
                                    <br>

                                    <?php

                                    if ($estadoSoliCompra == 'Solicitada a Secretaria SDO') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sdo.png" alt="Linea mesa de ayuda">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Entregado a la S.D.A' || $estadoSoliCompra == 'Autorizado por la S.D.A') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sda.png" alt="Linea SDA">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Derivado a Abastecimiento') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-abastecimiento.png" alt="Linea Abastecimiento">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'En Pañol') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-panol.png" alt="Linea Pañol">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Completada') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-completada.png" alt="Linea Pañol">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Solicitud Anulada') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sdo-anulada.png" alt="Linea Solicitud Anulada">
                                        </div>
                                    <?php
                                    } else if ($estadoSoliCompra == 'Rechazado por la S.D.A') {
                                    ?>
                                        <div class="row justify-content-center">
                                            <img src="img/linea-sda-rechazada.png" alt="Linea Rechazado sda">
                                        </div>
                                    <?php
                                    }

                                    ?>


                                    <br>

                                    <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Actualización
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Tipo de Actualización
                                                </th>

                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Observación
                                                </th>


                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM seguimiento_sc_sdo WHERE idSoliCompra = '$idSoliCompra' ORDER BY fechaActualizacion DESC";
                                            $res = mysqli_query($conexion, $query);



                                            while ($row = $res->fetch_assoc()) {
                                            ?>

                                                <tr>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php



                                                        $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaActualizacion']))));
                                                        $inicioHora = date('H:i', strtotime($row['fechaActualizacion']));

                                                        echo $inicioFecha . '<br>' . $inicioHora;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $cantidadArticuloSC = mb_strtoupper($row['tipoActualizacion']);
                                                        echo $cantidadArticuloSC;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                        <?php
                                                        $nomArticuloSC = mb_strtoupper($row['observacion']);
                                                        echo nl2br($nomArticuloSC);

                                                        ?>
                                                    </td>
                                                </tr>

                                            <?php

                                            }

                                            ?>
                                        </tbody>

                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Actualización
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Tipo de Actualización
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Observación
                                                </th>


                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>


                </div>
                <!-- /.timeline -->

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
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
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
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "responsive": {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({


                            header: function(row) {
                                var data = row.data();
                                return 'Informaci&oacute;n de Solicitud Telefonica: ';
                            },
                            autoWidth: true,
                        }),
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table col-12 text-justify align-items-center'
                        })
                    }
                },
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar :",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            //group add limit
            var maxGroup = "10";

            //add more fields group
            $(".addMore").click(function() {
                if ($('body').find('.fieldGroup').length < maxGroup) {
                    var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                    $('body').find('.fieldGroup:last').after(fieldHTML);

                } else {
                    alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                }
            });

            //remove fields group
            $("body").on("click", ".remove", function() {
                $(this).parents(".fieldGroup").remove();
            });
        });
    </script>
    <script>
        estadoSDA = document.getElementById('estadoSDA');
        motivo = document.getElementById('motivo');
        motivoText = document.getElementById('motivoText');

        estadoSDA.addEventListener('change', function(event) {

            valorEstado = estadoSDA.value;

            if (valorEstado == 'denegada') {
                motivo.style.display = 'block';
            } else if (valorEstado == '' || valorEstado == 'autorizada') {
                motivo.style.display = 'none';
                motivoText.removeAttribute("required");
            }


        })
    </script>
    <script src="js/dark-mode.js"></script>
    <!-- <script>
        $(document).ready(

            $('.sda').click(function(e) {

                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'info',
                    title: '¿Seguro que quieres cambiar el estado de la solicitud a "Entregado a la SDA"?',
                    showConfirmButton: true,
                    confirmButtonText: 'SI',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'NO',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/SoliCompra_sdo_cambiaEstado.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('cambiado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Estado de la solicitud modificado a "Entregado a la SDA" ',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 2100)
                            }
                        })
                    }
                })
            })


        )
    </script> -->
</body>

</html>