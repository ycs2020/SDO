<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
include '../sdo-funciones/carritoHerramientas.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



date_default_timezone_set('America/Santiago');


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Men&uacute; Carrito </title>

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
        <link rel="stylesheet" href="css/estilos.css">

        <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- CSS only -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> -->


        
        
        
    </head>
    <body class="hold-transition dark-mode">
        
        <!-- Site  wrapper -->
      
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h4>Retiro Materiales </h4>

                            </div>
                            <div class="col-sm-6">
                               
                                <br>
                                <br>
                                <div class="form-group">
                                    <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                        <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function toggleDark() {
                                // var element = document.getElementById("navBarPrincipal")
                                // element.classList.toggle("dark-mode")

                                document.body.classList.toggle("dark-mode");
                                                
                                }
                            </script>
                            <br>
                            <br>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Alertas Sweet Alert 2-->
                <!-- Alertas para herramientas--> 
                <?php 
                
                if(isset($_GET['sacar'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'error',
                            title: 'Error..',
                            text: 'No puede ingresar 0 materiales',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['stock'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'error',
                            title: 'Error..',
                            text: 'La cantidad solicitada no puede superar el stock disponible',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['eliminado'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'success',
                            title: 'OK',
                            text: 'El material ya fue eliminado de la lista',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['repetido'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'warning',
                            title: 'Ya existe..',
                            text: 'Esta Herramienta ya se encuentra seleccionada en el carrito',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['ingresado'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'success',
                            title: 'Añadido al carrito..',
                            text: 'Herramienta añadida exitosamente',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['guardado'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'success',
                            title: 'Exito.',
                            text: 'Solicitud Generada Correctamente',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['vacios'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'error',
                            title: 'Error.',
                            text: 'Debe Ingresar al menos una herramienta o material',
                            
                        })
                        </script>
                    <?php
                
                }

                


                if(isset($_GET['sacar1'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'error',
                            title: 'Error..',
                            text: 'No puede ingresar 0 materiales',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['stock1'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'error',
                            title: 'Error..',
                            text: 'La cantidad solicitada no puede superar el stock disponible',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['eliminado1'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'success',
                            title: 'OK',
                            text: 'El material ya fue eliminado de la lista',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['repetido1'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'warning',
                            title: 'Ya existe..',
                            text: 'El Material ya se encuentra seleccionada en el carrito',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['ingresado1'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'success',
                            title: 'Añadido al carrito..',
                            text: 'Material añadido exitosamente',
                            
                        })
                        </script>
                    <?php
                
                }

                if(isset($_GET['vaciopersona'])){
                    
                    ?>

                        <script>
                            Swal.fire({
                            icon: 'error',
                            title: 'Error..',
                            text: 'Seleccione departamento y solicitante',
                            
                        })
                        </script>
                    <?php
                
                }
   
                ?>
                         
                <!-- Main Content -->
                <section class="content">
                    <div class="container-fluid">

                    <!-- general form elements -->
                   <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Retiro Materiales</h3>
                        </div>

                        
                            
                        <div class="row">

                            <!-- left column -->
                            <div class="col-md-12">

                                    <!-- /.card-header -->
                                    <div class="card-body">

                                        

                                        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                            
                                            
                                            
                                                <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" style="font-weight: 900;">Herramientas</a>
                                           
                                            

                                              
                                        
                                        </div>

                                        
                                        <div class="tab-content" id="pills-tabContent">
                                             
                                            <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">Materiales Disponibles:</legend>
                                                                    
                                                <div class="table-responsive">
                                                    <table style="font-size: 90%;" id="example3" class="table table-bordered table-hover">
                                                        <thead class="bg-secondary">
                                                        <tr>    
                                                                <th style="vertical-align: middle;">
                                                                    <center>N°</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Imagen</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Código</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Nombre Descriptivo</center>  
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Stock Disponible</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Cantidad Arrendar</center>
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Agregar al Carrito</center> 
                                                                   
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            

                                                                <?php 

                                                                $queryMateriales = "SELECT * FROM pañol_materiales ORDER BY idMaterial  DESC";
                                                                $resMateriales = mysqli_query($conexion, $queryMateriales);

                                                                while($rowMateriales = $resMateriales->fetch_assoc()){

                                                                    $datos = [];
                                                                    
                                                                    
                                                                    $datos['idMaterial'] = $rowMateriales['idMaterial'];
                                                                    $datos['codMaterial'] = $rowMateriales['codMaterial'];
                                                                    $datos['nomMaterial'] = $rowMateriales['nomMaterial'];
                                                                    $datos['cantMaterial'] = $rowMateriales['cantMaterial'];
                                                                    $datos['imagen'] = $rowMateriales['imagen'];

                                                                    $cantMaterial = $datos['cantMaterial'];

                                                                    if($cantMaterial <= 2 ){

                                                                        $color = " style='background-color:red; color:black; font-weight:bold;font-size: 30px; vertical-align:middle'";
                                                                    }
            
                                                                    if($cantMaterial >=3 && $cantMaterial <= 9){
            
                                                                        $color = " style='background-color:yellow;color:black; font-size: 30px; font-weight:bold; vertical-align:middle'";
                                                                    }
            
                                                                    if($cantMaterial >=10){
            
                                                                        $color = " style='background-color:#62ed53;color:black; font-size: 30px; font-weight:bold; vertical-align:middle'";
                                                                    }
            



                                                                    ?>
                                                                    
                                                                    <tr>
                                                                        <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                            <center>
                                                                                <?php

                                                                              
                                                                                $idMaterial = mb_strtoupper($rowMateriales['idMaterial']);
                                                                                echo $idMaterial;

                                                                                ?>
                                                                            </center>
                                                                        </td>
                                                                        <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                            <center>
                                                                                <?php

                                                                              
                                                                                $imagen = $rowMateriales['imagen'];
                                                                                ?>
                                                                                    
                                                                                    <img src="img/<?php echo $imagen ?>" alt="Imagen de herramienta" width="100px">

                                                                                <?php 

                                                                                ?>
                                                                            </center>
                                                                        </td>
                                                                        <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                        <center>
                                                                            <?php

                                                                            $codMaterial = mb_strtoupper($rowMateriales['codMaterial']);
                                                                            echo $codMaterial;

                                                                            ?>
                                                                        </center>
                                                                        </td>
                                                                        <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                        <center>
                                                                            <?php

                                                                            $nomMaterial = mb_strtoupper($rowMateriales['nomMaterial']);
                                                                            echo $nomMaterial;

                                                                            ?>
                                                                        </center>
                                                                        </td>
                                                                        <td nowrap="nowrap" <?php echo $color ?> >
                                                                        <center>
                                                                            <?php

                                                                            $cantMaterial = mb_strtoupper($rowMateriales['cantMaterial']);
                                                                            echo $cantMaterial;

                                                                            ?>
                                                                        </center>
                                                                        </td>
                                                                        <td style="vertical-align:middle;font-weight:bold">
                                                                        <center>
                                                                            <?php

                                                                                ?> 
                                                                                    
                                                                                <form method="POST" action="../sdo-funciones/carritoAddMate.php" id="formdata">

                                                                                    <div class="form-group">
                                                                                        <label for="" class="label-control" required>Cantidad a reservar:</label>
                                                                                        <input type="number" required min="1" max="<?php echo $cantMaterial ?>"  class=" form-control" name="sacar">
                                                                                    </div>

                                                                               <?php      

                                                                            ?>
                                                                        </center>
                                                                        </td>
                                                                        <td style="vertical-align: middle;font-weight:bold">
                                                                            <center>
                                                                                <?php 

                                                                                                $idSoli = $_GET['idSoli'];

                                                                                    ?>
                                                                                        
                                                                           
                                                                                            <br>
                                                                                            <input type="hidden" name="id" id="id" value="<?php echo $idMaterial?>">
                                                                                            <input type="hidden" name="idSoli" id="id" value="<?php echo $idSoli?>">
                                                                                            <input type="hidden" name="codigo" id="codigo" value="<?php echo $codMaterial?>">
                                                                                            <input type="hidden" name="nombre" id="nombre" value="<?php echo $nomMaterial?>">
                                                                                            <input type="hidden" name="stock" id="stock" value="<?php echo $cantMaterial ?>">
                                                                                            <input type="hidden" name="cantidad" id="cantidad" value="<?php echo 1?>">
                                                                                        

                                                                                            <button name="btnAccion" value="Agregar"  id="añadir"  class="btn btn-info"><strong>Guardar Material</strong> </button>
                                                                                        </form>

                                                                                    <?php

                                                                                
                                                                                ?>
                                                                            </center>
                                                                        </td>
                                                                    </tr>

                                                                    <?php



                                                                }


                                                                ?>

                                                        </tbody>
                                                        <tfoot class="bg-secondary">
                                                            <tr>
                                                                <th style="vertical-align: middle;">
                                                                    <center>N°</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Imagen</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Código</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Nombre Descriptivo</center>  
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Stock Disponible</center> 
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Cantidad Arrendar</center>
                                                                </th>
                                                                <th style="vertical-align: middle;">
                                                                    <center>Agregar al Carrito</center> 
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>

                                                    
                                                </div>
                                            </fieldset>
                                            <br>    
                                                                
                                                <?php 

                                                   
                                                   
                                                
                                                ?>

                           

                                                
                                                                             
                                            </div>
                                           
                                            
                            

                            <div class="form-row fieldGroupCopy" style="display: none;">
                                        <div class="form-group col-md-3">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input type="text" required class="form-control" id="herramienta[]" name="herramienta[]" placeholder="Código Barras">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                                </div>
                                                <input type="text" min="1" placeholder="Nombre Producto" required class="form-control" id="cantidad[]" name="cantidad[]">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                                            </div>
                                        </div>

                            </div>
                            <script type="text/javascript">
                                            $(document).ready(function() {
                                            //group add limit
                                            var maxGroup = "100";

                                            //add more fields group
                                            $(".addMore").click(function() {
                                                if ($('body').find('.fieldGroup').length < maxGroup ) {
                                                    var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                                $('body').find('.fieldGroup:last').after(fieldHTML);

                                            } else {
                                                alert('Limite de Herramientas Completados. Solo se permiten ' + maxGroup + ' Herramientas.');
                                            }
                                        });

                                                                //remove fields group
                                    $("body").on("click", ".remove", function() {
                                        $(this).parents(".fieldGroup").remove();
                                    });
                                });
                            </script>

                                      
                                        

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                            <!--/.col  -->

                
                        
                    </div>

                        
                    </div>
                </section>
            </div><!-- .content wrapper-->

           

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->

        </div><!--.wrapper -->
        

        
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

       
      
        </script>
        <!-- Page specific script -->
        <script>
            $(function() {
        
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
                        "search": "Buscar:",
                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                        "paginate": {
                            "previous": "Anterior",
                            "next": "Siguiente"
                        }
                    }
                });
             
              
            });
         

            
        </script>
        <script>
            $(function() {
        
                $('#example3').DataTable({
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
                        "search": "Buscar:",
                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                        "paginate": {
                            "previous": "Anterior",
                            "next": "Siguiente"
                        }
                    }
                });
             
              
            });
         

            
        </script>
    


   
        
        <script src="js/alert.js"></script>
        <script src="js/fetch_api_arriendo.js"></script>
    </body>

</html>