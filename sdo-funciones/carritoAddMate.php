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
          
            if(is_numeric($_POST['codigo'])){

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

            $queryRetiroMaterial = "SELECT * FROM material_retirado WHERE idMaterial = ${id}";
            $resRetiroMaterial = mysqli_query($conexion, $queryRetiroMaterial);

            while($rowRetiroMaterial = $resRetiroMaterial->fetch_assoc()){

                $datosMaterial['idMaterial'] = $rowRetiroMaterial['idMaterial'];

                $idMaterial1 = $datosMaterial['idMaterial'];

            }

            if(!isset($_SESSION['CARRITOMATE'])){

                $material = array(

                    
                    'id' => $id,
                    'nombre' => $nombre,
                    'codigo' => $codigo,
                    'stock' => $stock,
                    'sacar' => $sacar

                );

                if($material['sacar'] <= 0 ){
                

                    header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito-mate.php?sacar=true&&idSoli=' . $idSoli);
                    return;
                }

                if($material['sacar'] > $material['stock'] ){
                

                    header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito-mate.php?stock=true&&idSoli' . $idSoli);
                    return;
                }

                if($idMaterial1 == $id){

                    echo "<script languaje='javascript' type='text/javascript'>;window.opener.document.location='http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/listado-soli-ticket.php?existeHerra=true';;window.close();</script>";
                    exit;
    
                }


                $_SESSION['CARRITOMATE'][0] = $material;
                //header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?ingresado=true');


            }else{

                $idMateriales = array_column($_SESSION['CARRITOMATE'],'id');

                if(in_array($id, $idMateriales)){


                    header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito-mate.php?repetido=true&&idSoli=' . $idSoli);
                    return;

                }else{
                    $numeroMateriales = count($_SESSION['CARRITOMATE']);
                    $material = array(

                        
                        'id' => $id,
                        'nombre' => $nombre,
                        'codigo' => $codigo,
                        'stock' => $stock, 
                        'sacar' => $sacar

                    );

                    if($material['sacar'] <= 0 ){
                

                        header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito-mate.php?idSoli=' . $idSoli . '&&sacar=true');
                        return;
                    }

                    if($material['sacar'] > $material['stock']){

                        header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito-mate.php?stock=true&&idSoli=' . $idSoli);
                        return;

                    }

                    if($idMaterial1 == $id){

                        echo "<script languaje='javascript' type='text/javascript'>;window.opener.document.location='http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/listado-soli-ticket.php?existeHerra=true';;window.close();</script>";
                        exit;
        
                    }

                    array_push($_SESSION['CARRITOMATE'], $material);
                   // header('Location: /TrabajarSDO_Pruebas/sdo-bodega-panol/modificar-carrito.php?ingresado=true');

            }

            if(!empty($_SESSION['CARRITOMATE']) || !empty($_SESSION['CARRITO'])){

                if(!empty($_SESSION['CARRITOMATE'])){
                    foreach($_SESSION['CARRITOMATE'] AS $indice => $material){

                        $codigo = $material['codigo'];
                        $nombre = $material['nombre'];
                        $cantidad = $material['sacar'];
                        $stock = $material['stock'];
                        $idMaterial = $material['id'];

                        $resta = $stock - $cantidad;


                        $queryInsertMate = "INSERT INTO material_retirado(codMaterial, nomMaterial  ,cantidadMaterial,idSolicitud,idMaterial)
                        VALUES ($codigo, '$nombre',$cantidad, $idSoli, $idMaterial)";
                        $resInsertMate = mysqli_query($conexion, $queryInsertMate);

                        $queryUpdateMate = "UPDATE pañol_materiales SET cantMaterial = '${resta}' WHERE idMaterial = '${idMaterial}'";
                        $resUpdateMate = mysqli_query($conexion, $queryUpdateMate);

                        unset($_SESSION['CARRITOMATE'][$indice]);
                    }

                   
                }

                

                if($resInsertMate || $resInsert){
                    echo "<script languaje='javascript' type='text/javascript'>;window.opener.document.location='http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/listado-soli-ticket.php?nuevoMate=true';;window.close();</script>";
                }


            
        }

            
        }
        break;
        
        case 'Guardar':

          


        break;
        }
    }