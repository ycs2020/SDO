<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos Cotizaci&oacute;n SC</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Productos Cotizaci&oacute;n SC</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalAgregarProducto">
                                            <i class="fa fa-plus"></i> Nuevo Producto
                                        </button>
                                    </div>
                                </div>

                                <table id="tablaItems" class="table table-bordered table-striped">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Valor</th>
                                        <th>Cantidad</th>
                                        <th>Medida</th>
                                        <th>Imagen</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Valor</th>
                                        <th>Cantidad</th>
                                        <th>Medida</th>
                                        <th>Imagen</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>

                    </div>


                </div>
                <!-- /.row -->
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
<script src="js/dark-mode.js"></script>

<script>
    $(document).ready(function () {

        var tabla = $('#tablaItems').DataTable({
            "ajax": "/sdo-includes/cotizacion-items/item_cotizacion_listar.php",
            "columns": [
                { "data": "idItem" },
                { "data": "nomItem" },
                { "data": "valorItem" },
                { "data": "cantidadItem" },
                { "data": "medidaItem" },
                {
                    "data": "imagenItem",
                    "render": function (data) {
                        return data
                            ? `${data}`
                            : '<span class="badge badge-danger">Sin imagen</span>';
                    }
                },
                {
                    "data": "idItem",
                    "render": function (id) {
                        return `
                        <button class="btn btn-info btn-sm" onclick="editar(${id})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="eliminar(${id})">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;
                    }
                }
            ]
        });

        // Cargar datos en modal editar
        window.editar = function(id){
            $.ajax({
                url: "../sdo-includes/cotizacion-items/item_cotizacion_obtener.php",
                type: "POST",
                data: { idItem: id },
                dataType: "json", // 👈 FORZAMOS JSON REAL
                success: function(d){
                    console.log(d.medidaItem);

                    $("#edit_idItem").val(d.idItem);
                    $("#edit_nomItem").val(d.nomItem);
                    $("#edit_enlaceItem").val(d.enlaceItem);
                    $("#edit_caracteristicaItem").val(d.caracteristicaItem);
                    $("#edit_valorItem").val(d.valorItem);
                    $("#edit_cantidadItem").val(d.cantidadItem);
                    $("#edit_medidaItem").val(d.medidaItem);

                    $("#modalEditarProducto").modal("show");
                },
                error: function(xhr){
                    console.log("RESPUESTA INVALIDA:", xhr.responseText);
                    Swal.fire("Error", "La respuesta del servidor no es JSON válido", "error");
                }
            });
        }


// Eliminar producto
        window.eliminar = function(id){
            Swal.fire({
                title: "¿Eliminar este producto?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar"
            }).then((r) => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: "../sdo-includes/cotizacion-items/item_cotizacion_eliminar.php",
                        type: "POST",
                        data: { idItem: id },
                        dataType: "json",
                        success: function(resp) {
                            console.log("🧹 RESPUESTA ELIMINACIÓN:", resp);
                            if (resp.status === "ok") {
                                Swal.fire("Listo", "Eliminado correctamente", "success");
                                $('#tablaItems').DataTable().ajax.reload();
                            } else {
                                Swal.fire("Error", resp.msg || "No se pudo eliminar", "error");
                            }
                        },
                        error: function(xhr) {
                            console.error("❌ ERROR ELIMINANDO:", xhr.responseText);
                            Swal.fire("Error", "No se pudo procesar la eliminación", "error");
                        }
                    });
                }
            });
        }


    });


</script>

<div class="modal fade" id="modalAgregarProducto">
    <div class="modal-dialog modal-lg">
        <form id="formAgregarProducto" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Nuevo Producto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nomItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Enlace</label>
                        <input type="text" name="enlaceItem" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Características</label>
                        <textarea name="caracteristicaItem" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Valor</label>
                        <input type="number" name="valorItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" name="cantidadItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Medida</label>
                        <input type="text" name="medidaItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Imagen</label>
                        <input type="file" name="imagenItem" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    $("#formAgregarProducto").submit(function(e){
        console.log("ENVIANDO FORMULARIO...");
        e.preventDefault();
        $.ajax({
            url: "../sdo-includes/cotizacion-items/item_cotizacion_crear.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            dataType: "json", // 🔥 asegura que ya venga parseado
            success: function(d){
                console.log("📌 RESPUESTA SERVER:", d);
                if(d.status === "ok"){
                    Swal.fire("Correcto", "Producto agregado", "success");
                    $("#modalAgregarProducto").modal("hide");
                    $('#tablaItems').DataTable().ajax.reload();
                } else {
                    Swal.fire("Error", d.debug || "Error desconocido", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ ERROR AJAX:", xhr.responseText);
                Swal.fire("Error", "No se pudo guardar el producto", "error");
            }
        });

    });
</script>

<div class="modal fade" id="modalEditarProducto">
    <div class="modal-dialog modal-lg">
        <form id="formEditarProducto" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <!-- ID del producto oculto -->
                    <input type="hidden" name="edit_idItem" id="edit_idItem">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="edit_nomItem" name="edit_nomItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Enlace</label>
                        <input type="text" id="edit_enlaceItem" name="edit_enlaceItem" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Características</label>
                        <textarea id="edit_caracteristicaItem" name="edit_caracteristicaItem" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Valor</label>
                        <input type="number" id="edit_valorItem" name="edit_valorItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" id="edit_cantidadItem" name="edit_cantidadItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Medida</label>
                        <input type="text" id="edit_medidaItem" name="edit_medidaItem" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nueva imagen (opcional)</label>
                        <input type="file" name="edit_imagenItem" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Guardar cambios</button>
                </div>

            </div>
        </form>
    </div>
</div>


<script>
    $("#formEditarProducto").submit(function(e){
        e.preventDefault();

        console.log("🧪 Enviando formEditarProducto...");
        console.log("🧪 edit_idItem:", $("#edit_idItem").val());

        $.ajax({
            url: "../sdo-includes/cotizacion-items/item_cotizacion_editar.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(resp){
                console.log("📌 RESPUESTA SERVER:", resp);
                if(resp.status === "ok"){
                    Swal.fire("Correcto", "Producto Modificado", "success");
                    $("#modalEditarProducto").modal("hide");
                    $('#tablaItems').DataTable().ajax.reload();
                } else {
                    Swal.fire("Error", resp.msg || "Error desconocido", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ ERROR AJAX:", xhr.responseText);
                Swal.fire("Error", "No se pudo modificar el producto", "error");
            }
        });
    });

</script>



</body>

</html>