<?php

include '../sdo-funciones/conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mensaje = "";

if(isset($_POST['btnAccion'])){

    $idSoli = $_POST['idSoli'];

   


    switch($_POST['btnAccion']){

        //RECUPERAR Y GUARDAR LA INFORMACIÓN DE LA  HERRAMIENTA EN UN ARRAY 
        case 'Agregar':

            if(is_numeric($_POST['id'])){

                $id = $_POST['id'];
                $mensaje.= "  ID: " . $id . "<br>";
            }else{
               

                $mensaje = "Upss... ID no valido " . $id;
            }
            if(is_string($_POST['nombre'])){

                $nombre = $_POST['nombre'];
                $mensaje.= "  Nombre: " . $nombre . "<br>";
            }else{

                $mensaje = "Upss... Nombre no valido " . $nombre;
            }
          
            if($_POST['codigo']){

                $codigo = $_POST['codigo'];
                $mensaje.= "  Código: " . $codigo . "<br>";
            }else{

                $mensaje = "Upss... Código no valido " . $codigo;
            }
            if(is_numeric($_POST['sacar'])){

                $sacar = $_POST['sacar'];
                $mensaje.= "  Cantidad: " . $sacar . "<br>";
            }else{

                $mensaje = "Upss... Código no valido " . $sacar;
            }

            $stock = $_POST['stock'];

            //VALIDACIÓN DE HERRAMIENTA YA AGREGADA

            $queryHerraArriendo = "SELECT * FROM herra_en_arriendo WHERE idHerramienta = ${id}";
            $resHerraArriendo = mysqli_query($conexion, $queryHerraArriendo);

            while($rowHerraArriendo = $resHerraArriendo->fetch_assoc()){

                $datosHerra['idHerramienta'] = $rowHerraArriendo['idHerramienta'];

                $idHerramienta1 = $datosHerra['idHerramienta'];

            }

            // var_dump($resHerraArriendo);
            // exit;

         

            if(!isset($_SESSION['CARRITOADDHERA'])){

                $herramienta = array(

                    
                    'id' => $id,
                    'nombre' => $nombre,
                    'codigo' => $codigo,
                    'stock' => $stock,
                    'sacar' => $sacar

                );

                if($herramienta['sacar'] <= 0 ){
                

                    header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?sacar=true&&idSoli=' . $idSoli);
                    return;
                }

                if($herramienta['sacar'] > $herramienta['stock'] ){
                

                    header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?stock=true&&idSoli' . $idSoli);
                    return;
                }

             
                // if($idHerramienta1 == $id){

                //     echo "<script languaje='javascript' type='text/javascript'>;window.opener.document.location='http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/listado-soli-ticket.php?existeHerra=true';;window.close();</script>";
                //     exit;
    
                // }

                $_SESSION['CARRITOADDHERA'][0] = $herramienta;
                //header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?ingresado=true');

              


            }else{

                $idHerramientas = array_column($_SESSION['CARRITOADDHERA'],'id');

                if(in_array($id, $idHerramientas)){


                    header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?repetido=true&&idSoli='. $idSoli);
                    return;

                }else{
                    $numeroHerramientas = count($_SESSION['CARRITOADDHERA']);
                    $herramienta = array(

                        
                        'id' => $id,
                        'nombre' => $nombre,
                        'codigo' => $codigo,
                        'stock' => $stock, 
                        'sacar' => $sacar

                    );

                    if($herramienta['sacar'] <= 0 ){
                

                        header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?idSoli=' . $idSoli . '&&sacar=true');
                        return;
                    }

                    if($herramienta['sacar'] > $herramienta['stock']){

                        header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?stock=true&&idSoli=' . $idSoli);
                        return;

                    }

                    // if($idHerramienta1 == $id){

                    //     echo "<script languaje='javascript' type='text/javascript'>;window.opener.document.location='http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/listado-soli-ticket.php?existeHerra=true';;window.close();</script>";
                    //     exit;
        
                    // }

                    array_push($_SESSION['CARRITOADDHERA'], $herramienta);
                   // header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?ingresado=true');

            }

            if(!empty($_SESSION['CARRITOADDHERA']) || !empty($_SESSION['CARRITO'])){


                if(!empty($_SESSION['CARRITOADDHERA'])){
                    foreach($_SESSION['CARRITOADDHERA'] AS $indice => $herramienta){

                        $codigo = $herramienta['codigo'];
                        $nombre = $herramienta['nombre'];
                        $cantidad = $herramienta['sacar'];
                        $stock = $herramienta['stock'];
                        $idHerramienta = $herramienta['id'];

                        $resta = $stock - $cantidad;


                        $queryInsert = "INSERT INTO herra_en_arriendo(codHerramienta, nomHerramienta  ,cantidadHerramienta,idSolicitud,idHerramienta)
                        VALUES ('$codigo', '$nombre' ,$cantidad, $idSoli, $idHerramienta)";

                      
                        $resInsert = mysqli_query($conexion, $queryInsert);

                        $queryUpdate = "UPDATE pañol_herramientas SET cantHerramienta = '${resta}' WHERE idHerramienta = '${idHerramienta}'";
                        $resUpdate = mysqli_query($conexion, $queryUpdate);

                        unset($_SESSION['CARRITOADDHERA'][$indice]);
                    }

                    
                } 

               

                if( $resInsert){
                    echo "<script languaje='javascript' type='text/javascript'>;window.opener.document.location='http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/listado-soli-ticket.php?nuevo=true';;window.close();</script>";
                }


            
        }

            
        }
        break;
        
        case 'Guardar':

          


        break;
        }
    }