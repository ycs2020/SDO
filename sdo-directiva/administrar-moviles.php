<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Popper JS-->
    <style>
        #tooltip {
            background: #8000ff;
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            font-size: 13px;
            border-radius: 4px;
            display: none;
        }

        #tooltip[data-show] {
            display: block;
        }

        #arrow,
        #arrow::before {
            position: absolute;
            width: 8px;
            height: 8px;
            background: inherit;
        }

        #arrow {
            visibility: hidden;
        }

        #arrow::before {
            visibility: visible;
            content: '';
            transform: rotate(45deg);
        }

        #tooltip[data-popper-placement^='top']>#arrow {
            bottom: -4px;
        }

        #tooltip[data-popper-placement^='bottom']>#arrow {
            top: -4px;
        }

        #tooltip[data-popper-placement^='left']>#arrow {
            right: -4px;
        }

        #tooltip[data-popper-placement^='right']>#arrow {
            left: -4px;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="wrapper">
        <?php
        include '../sdo-templates/directiva-sidebar.php';

        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- AVISOS -->
            <?php

            if (isset($_GET['errorGuardar'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Eror al guardar la información del vehículo, code: 1',
                    })
                </script>

            <?php
            }

            if (isset($_GET['exitoGuardar'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito!',
                        text: 'La información del vehículo se guardo correctamente.',
                    })
                </script>

            <?php
            }

            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Listado de Vehiculos Ingresados</b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>
                                    <br>
                                    <br>


                                    <table id="table_equipos" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">ID</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Patente</th>
                                                <th scope="col" class="text-center">Marca</th>
                                                <th scope="col" class="text-center">Modelo</th>
                                                <th scope="col" class="text-center">Servicio</th>
                                                <th scope="col" class="text-center">Valor Iva</th>
                                                <th scope="col" class="text-center">Estado</th>
                                                <th scope="col" class="text-center"><i class="fas fa-exclamation"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT `idUnico`, `idMovil`, `folio`, `nomVehiculo`, `patenteVehiculo`, `marcaVehiculo`, `modeloVehiculo`, `numInventario`, `servicioVehiculo`, `numChasis`, `numMotor`, `frabricante`, `fechaCompra`, `ordenCompra`, `valorConIva`, `estadoVehiculo`, `fechaRegistro` FROM `moviles_movilizacion` ";
                                            $res = mysqli_query($conexion, $sql);
                                            while ($row = $res->fetch_assoc()) {
                                                $datos = [];
                                                $datos['idUnico'] = $row['idUnico'];
                                                $datos['nomVehiculo'] = $row['nomVehiculo'];
                                                $datos['patenteVehiculo'] = $row['patenteVehiculo'];
                                                $datos['marcaVehiculo'] = $row['marcaVehiculo'];
                                                $datos['modeloVehiculo'] = $row['modeloVehiculo'];
                                                $datos['servicioVehiculo'] = $row['servicioVehiculo'];
                                                $datos['valorConIva'] = $row['valorConIva'];
                                                $datos['estadoVehiculo'] = $row['estadoVehiculo'];
                                                $datos['idMovil'] = $row['idMovil'];
                                                $folio = $datos['idUnico'];
                                                $nomVehiculo = $datos['nomVehiculo'];
                                                $patenteVehiculo = $datos['patenteVehiculo'];
                                                $marcaVehiculo = $datos['marcaVehiculo'];
                                                $modeloVehiculo = $datos['modeloVehiculo'];
                                                $servicioVehiculo = $datos['servicioVehiculo'];
                                                $valorConIva = $datos['valorConIva'];
                                                $estadoVehiculo = $datos['estadoVehiculo'];
                                                $idMovil = $datos['idMovil'];
                                            ?>
                                                <tr>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $folio ?> </td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $nomVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $patenteVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $marcaVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;">
                                                        <?php

                                                        if (empty($modeloVehiculo)) {

                                                            echo '-';
                                                        } else {
                                                            echo $modeloVehiculo;
                                                        }



                                                        ?>
                                                    </td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;"><?php echo $servicioVehiculo ?></td>
                                                    <td class="text-center font-weight-bold" style="font-size:12px;">
                                                        <?php

                                                        if (empty($valorConIva)) {

                                                            echo '-';
                                                        } else {
                                                            $valorConIva = moneda_chilena($valorConIva);
                                                            echo $valorConIva;
                                                        }

                                                        ?>

                                                    </td>
                                                    <?php

                                                    if ($estadoVehiculo == 'OPERATIVA') {
                                                    ?>
                                                        <td class="text-center font-weight-bold  text-success" style="font-size: 12px"><?php echo '<i class="fas fa-check"></i> ' .  strtoupper($estadoVehiculo) ?></td>
                                                    <?php
                                                    } else if ($estadoVehiculo == 'MANTENCION PREVENTIVA' || $estadoVehiculo == 'mantencion preventiva') {
                                                    ?>
                                                        <td class="text-center font-weight-bold text-warning  " style="font-size: 12px"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' .  strtoupper($estadoVehiculo) ?></td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td class=" text-center font-weight-bold text-danger " style="font-size: 12px"><?php echo '<i class="fas fa-skull-crossbones"></i> ' .  strtoupper($estadoVehiculo) ?></td>
                                                    <?php
                                                    }

                                                    ?>
                                                    <td class="text-center" style="font-size:12px;">

                                                        <form action="visualizarMovil.php" method="POST" style="display: inline;">
                                                            <input type="hidden" id="idMovil" name="idMovil" value="<?php echo $idMovil ?>">
                                                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">ID</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Patente</th>
                                                <th scope="col" class="text-center">Marca</th>
                                                <th scope="col" class="text-center">Modelo</th>
                                                <th scope="col" class="text-center">Servicio</th>
                                                <th scope="col" class="text-center">Valor Iva</th>
                                                <th scope="col" class="text-center">Estado</th>
                                                <th scope="col" class="text-center"><i class="fas fa-exclamation"></i></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div><!-- .tabla-responsive -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col  -->
        </div>
        <!-- /.row -->
        </section>
        <!-- /.content -->
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

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>
        $(document).ready(function() {
            $('#table_equipos').DataTable({
                "paging": true,
                "iDisplayLength": 50,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "Sin registros para mostrar.",
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                },
                // initComplete: function() {
                //     this.api().columns([3]).every(function() {
                //         var column = this;
                //         var select = $('<select style="width: 40%;"><option value="">Servicio</option></select>')
                //             .appendTo($(column.header()).empty())
                //             .on('change', function() {
                //                 var val = $.fn.dataTable.util.escapeRegex(
                //                     $(this).val()
                //                 );

                //                 column
                //                     .search(val ? '^' + val + '$' : '', true, false)
                //                     .draw();
                //             });

                //         column.data().unique().sort().each(function(d, j) {
                //             select.append('<option value="' + d + '">' + d + '</option>')
                //         });
                //     });
                // }
            });
        });
    </script>
    <script>
        $(document).ready(
            $('.eliminar').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar este vehículo?',
                    text: 'Esta acción no se puede revertir',
                    showConfirmButton: true,
                    confirmButtonText: 'Si , eliminar',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No, lo pensare',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/eliminar-moviles.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro del vehículo eliminado',
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
    </script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script>
        const button = document.querySelector('#agregar');
        const tooltip = document.querySelector('#tooltip');

        const popperInstance = Popper.createPopper(button, tooltip, {
            placement: 'right',
            modifiers: [{
                name: 'offset',
                options: {
                    offset: [0, 8],
                },
            }, ],
        });

        function show() {
            // Make the tooltip visible
            tooltip.setAttribute('data-show', '');

            // Enable the event listeners
            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: 'eventListeners',
                        enabled: true
                    },
                ],
            }));

            // Update its position
            popperInstance.update();
        }

        function hide() {
            // Hide the tooltip
            tooltip.removeAttribute('data-show');

            // Disable the event listeners
            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: 'eventListeners',
                        enabled: false
                    },
                ],
            }));
        }

        const showEvents = ['mouseenter', 'focus'];
        const hideEvents = ['mouseleave', 'blur'];

        showEvents.forEach((event) => {
            button.addEventListener(event, show);
        });

        hideEvents.forEach((event) => {
            button.addEventListener(event, hide);
        });
    </script>


</body>

</html>