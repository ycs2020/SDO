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
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- AVISOS -->
            <?php

            if (isset($_GET['noGasto'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al ver los gastos, direccionado a la lista de equipos',
                    })
                </script>

            <?php
            }

            if (isset($_GET['exito'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Muy Bien!',
                        text: 'Exito al ingresar equipo al sistema.',
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
                            <h1 class="card-title"> <b> Listado de todos los equipos ingresados</b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>
                                    <a class="btn  bg-purple" href="ingresar-equipo-hojadevida.php" aria-describedby="tooltip" id="agregar"><i class="fas fa-plus"></i><b> Equipo</b> </a>
                                    <div id="tooltip" role="tooltip">
                                        <b>Agregar un nuevo equipo</b>
                                        <div id="arrow" data-popper-arrow></div>
                                    </div>
                                    <br>
                                    <br>

                                    <table id="table_equipos" class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">N°</th>
                                                <th scope="col">Folio</th>
                                                <th scope="col">Nombre Equipo</th>
                                                <th scope="col">Servicio</th>
                                                <th scope="col">Marca</th>
                                                <th scope="col">Modelo</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $sqlEquipo = "SELECT * FROM hoja_de_vida";
                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                            $num = 1;

                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['idHoja'] = $rowEquipo['idHoja'];
                                                $datos['folio'] = $rowEquipo['folio'];
                                                $datos['nomEquipo'] = $rowEquipo['nomEquipo'];
                                                $datos['idServicio'] = $rowEquipo['idServicio'];
                                                $datos['marca'] = $rowEquipo['marca'];
                                                $datos['modelo'] = $rowEquipo['modelo'];
                                                $datos['estadoActual'] = $rowEquipo['estadoActual'];

                                                $idHoja = $datos['idHoja'];
                                                $folio = $datos['folio'];
                                                $nomEquipo = $datos['nomEquipo'];
                                                $idServicio = $datos['idServicio'];
                                                $marca = $datos['marca'];
                                                $modelo = $datos['modelo'];
                                                $estadoActual = $datos['estadoActual'];

                                            ?>
                                                <tr>
                                                    <td style="font-size: 12px"><?php echo $num ?> </td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($folio)  ?></td>
                                                    <td style="font-size: 12px"><?php echo mb_convert_case($nomEquipo, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                    <td style="font-size: 12px"><?php

                                                                                $sqlServicio = "SELECT * FROM servicios WHERE idServicio = '$idServicio'";
                                                                                $resServicio = mysqli_query($conexion, $sqlServicio);

                                                                                while ($rowServicio = $resServicio->fetch_assoc()) {
                                                                                    $datos = [];
                                                                                    $datos['nomServicio'] = $rowServicio['nomServicio'];
                                                                                    $nomServicio = $datos['nomServicio'];
                                                                                }

                                                                                echo $nomServicio;
                                                                                ?></td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($marca)  ?></td>
                                                    <td style="font-size: 12px"><?php echo strtoupper($modelo)  ?></td>
                                                    <?php

                                                    if ($estadoActual == 'Operativo') {
                                                    ?>
                                                        <td class="font-weight-bold  text-success" style="display: inline-block;font-size: 12px"><?php echo '<i class="fas fa-check"></i> ' .  strtoupper($estadoActual) ?></td>
                                                    <?php
                                                    } else if ($estadoActual == 'Mantenimiento') {
                                                    ?>
                                                        <td class="font-weight-bold text-warning  " style="display: inline-block;font-size: 12px"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' .  strtoupper($estadoActual) ?></td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td class="font-weight-bold text-danger " style="display: inline-block;font-size: 12px"><?php echo '<i class="fas fa-skull-crossbones"></i> ' .  strtoupper($estadoActual) ?></td>
                                                    <?php
                                                    }

                                                    ?>
                                                    <td>
                                                        <form action="visualizarEquipo.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="idHoja" id="idHoja" value="<?php echo $idHoja ?>">
                                                            <button type="submit" class="btn btn-info btn-sm"> <i class="fas fa-eye"></i></button>
                                                        </form>

                                                        <form action="../sdo-pdf/SDO-Hoja-Vida.php" target="_blank" method="POST" style="display:inline;">
                                                            <input type="hidden" name="idHoja" id="idHoja" value="<?php echo $idHoja ?>">
                                                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-file-pdf"></i></button>
                                                        </form>

                                                        <a href="" class="btn btn-danger btn-sm eliminar" id="<?php echo $idHoja ?>"><i class="fas fa-trash-alt"></i></a>
                                                    </td>

                                                </tr>
                                            <?php
                                                $num++;
                                            }
                                            ?>
                                        </tbody>
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
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                },
                initComplete: function() {
                    this.api().columns([3]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 40%;"><option value="">Servicio</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });


                }
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
                    title: '¿Eliminar este equipo?',
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
                            url: '../sdo-funciones/eliminar-hoja-vida.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro del equipo eliminado',
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