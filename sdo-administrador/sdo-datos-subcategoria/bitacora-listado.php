tdt<?php

    include '../../sdo-funciones/conexion.php';
    include '../../sdo-funciones/funciones.php';

    if (isset($_POST['idSubcategoria'])) {

        $idSubcategoria = trim($_POST['idSubcategoria']);

        $cadena = '';

        $numReporte = 1;

        if (!empty($idSubcategoria) && $idSubcategoria > 0) {

            $contarObservaciones = contarObservacionesSubCategoria($idSubcategoria);

            if ($contarObservaciones > 0) {
                $query = "SELECT * FROM bitacora WHERE idSubCategoria = '" . $idSubcategoria . "' ORDER BY fechaBitacora DESC";

                $res = mysqli_query($conexion, $query);



                while ($row = $res->fetch_assoc()) {

                    if ($row['idSubcategoria'] > 0) {
                        $nomSubcategoria = getNomSubCategoria($row["idSubcategoria"]);
                    } else {
                        $nomSubcategoria = 'Error al Obtener Categoria';
                    }

                    $cadena .= '<tr>';

                    $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%"><center>' . $numReporte . '</center></td>';

                    if ($row['fechaBitacora'] == 0) {

                        $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%;"><center>Error al Obtener Fecha</center></td>';
                    } else {
                        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaBitacora'])));
                        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaBitacora'])));

                        $fechaBitacora2 = strtotime($row['fechaBitacora']);

                        $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%;"><center>' . $inicioFecha . '<br>' . $inicioHora . '</center></td>';
                    }

                    
                    // $cadena .= '<td style="vertical-align:middle; width:60%;" align="justify">' . nl2br($row["observacionBitacora"]) . '</td>';
                    $cadena .= '<td style="vertical-align:middle; width:10%;"><center>';

                    $cadena .= '<button type="button" data-toggle="modal" data-target="#modalObserva' . $row['idBitacora'] . '" id="inputMozi" class="btn btn-primary">Ver Observaci&oacute;n</button>';

                    $cadena .= '<div class="modal fade" id="modalObserva' . $row['idBitacora'] . '" name="modalObserva' . $row['idBitacora'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';

                    $cadena .= '<div class="modal-dialog" role="document">';

                    $cadena .= '<div class="modal-content">';

                    $cadena .= '<div class="modal-header bg-primary">';

                    $cadena .= '<h5 class="modal-title" id="exampleModalLabel">Visualizando Observaci&oacute;n</h5>';

                    $cadena .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';

                    $cadena .= '<span aria-hidden="true">&times;</span>';

                    $cadena .= '</button>';

                    $cadena .= '</div>';

                    $cadena .= '<div class="modal-body">';

                    $cadena .= '<div style="text-align:left;" class="form-group">';

                    $cadena .= '<p><b>Categoria: </b>' . $nomSubcategoria . '</p>';

                    $cadena .= '<p><b>Funcionario: </b>' . $row["tecnicoBitacora"] . '</p>';

                    $cadena .= '<p><b>Fecha Observacion: </b>' . fechaEspañol($fechaBitacora2) . ' a las ' . $inicioHora . '</p>';

                    $cadena .= '<label for="exampleInputBorder">Observacion: &nbsp;</label><br>';

                    $cadena .= '<textarea id="observacionBitacora" style="resize:none;" readonly="readonly" name="observacionBitacora" class="form-control bg-light" rows="15" placeholder="Observaciones...">' . $row["observacionBitacora"] . '</textarea>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '<div class="modal-footer">';

                    $cadena .= '<button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</center></td>';

                    $cadena .= '<td nowrap="nowrap"  style="vertical-align:middle; width:10%;" align="center">' . $nomSubcategoria . '</td>';

                    $cadena .= '<td style="vertical-align:middle; width:10%" align="center">' . $row['tecnicoBitacora'] . '</td>';

                    $cadena .= '<td style="vertical-align:middle; width:10%; align="center"><center>';

                    $cadena .= '<button type="button" data-toggle="modal" data-target="#modalModificar' . $row['idBitacora'] . '" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>';

                    $cadena .= '<form method="post" action="../sdo-funciones/admin-modificarBitacora.php">';

                    $cadena .= '<div class="modal fade" id="modalModificar' . $row['idBitacora'] . '" name="modalModificar' . $row['idBitacora'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';

                    $cadena .= '<div class="modal-dialog" role="document">';

                    $cadena .= '<div class="modal-content">';

                    /// MODAL HEADER

                    $cadena .= '<div class="modal-header bg-primary">';

                    $cadena .= '<h5 class="modal-title" id="exampleModalLabel">Modificar Observaci&oacute;n</h5>';

                    $cadena .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';

                    $cadena .= '<span aria-hidden="true">&times;</span>';

                    $cadena .= '</button>';

                    $cadena .= '</div>';

                    /// FIN MODAL HEADER

                    /// MODAL BODY

                    $cadena .= '<div class="modal-body">';

                    $cadena .= '<div style="text-align:left;" class="form-group">';

                    $cadena .= '<div><p><b>Tecnico: </b>' . $row['tecnicoBitacora'] . '</p>';

                    $cadena .= '</div>';

                    $cadena .= '<div style="text-align:left;" class="form-group">';

                    $cadena .= '<div><label for="exampleInputDepartamento">Categoria:</label><br>';

                    $cadena .= '<select class="custom-select" name="subcategoriaModificar" id="subcategoriaModificar">';

                    $rowSubCategoria2 = $row['idSubcategoria'];

                    $idCategoria = obtenerIDBitacora();

                    $querySubcategoria = "SELECT * FROM subcategorias WHERE idCategoria = '" . $idCategoria . "' ORDER BY nomSubcategoria ASC";

                    $resSubcategoria = mysqli_query($conexion, $querySubcategoria);

                    while ($rowSubcategoria = $resSubcategoria->fetch_assoc()) {

                        if ($rowSubcategoria['idSubcategoria'] == $rowSubCategoria2) {

                            $cadena .= '<option value="' . $rowSubcategoria['idSubcategoria'] . '" selected="selected">' . $rowSubcategoria['nomSubcategoria'] . '</option>';
                        } else {
                            $cadena .= '<option value="' . $rowSubcategoria['idSubcategoria'] . '">' . $rowSubcategoria['nomSubcategoria'] . '</option>';
                        }
                    }

                    // $cadena .= '<option value="1">'.$querySubcategoria.'</option>';            

                    $cadena .= '</select>';

                    $cadena .= '</div><br>';

                    $cadena .= '<textarea id="modObservacionBitacora" style="resize:none;" name="modObservacionBitacora" class="form-control" rows="15" placeholder="Observaciones...">' . $row["observacionBitacora"] . '</textarea>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    /// FIN MODAL BODY

                    /// MODAL FOOTER

                    $cadena .= '<div class="modal-footer">';

                    $cadena .= '<input type="hidden" name="idBitacora" id="idBitacora" value="' . $row['idBitacora'] . '">';

                    $cadena .= '<button type="submit" class="btn btn-primary">Modificar</button>';

                    $cadena .= '<button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>';

                    $cadena .= '</div>';

                    /// FIN MODAL FOOTER

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</form>';

                    $cadena .= '</center></td>';

                    $cadena .= '<td style="vertical-align:middle; width:10%; align="center"><center>';

                    $cadena .= '<form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/bitacora-eliminar.php" method="post">';

                    $cadena .= '<input type="hidden" name="idBitacora" value="' . $row['idBitacora'] . '">';

                    $cadena .= '<button type="submit" id="inputMozi" class="btn btn-block btn-danger"><i class="fa fa-trash"></i></button>';

                    $cadena .= '</form>';

                    $cadena .= '</center></td>';
                                                                
                    $cadena .= '</tr>';

                    $numReporte++;
                }
            } else {
                $cadena .= '<tr>';
                $cadena .= '<td colspan="7"><center>No Existen Registros.</center></td>';
                $cadena .= '</tr>';
            }
        } else if (!empty($idSubcategoria) && $idSubcategoria == 'todo') {

            $contarObservaciones = contarObservaciones($idSubcategoria);

            if ($contarObservaciones > 0) {
                $query = "SELECT * FROM bitacora ORDER BY fechaBitacora DESC";

                $res = mysqli_query($conexion, $query);



                while ($row = $res->fetch_assoc()) {

                    $cadena .= '<tr>';

                    $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%;"><center>' . $numReporte . '</center></td>';

                    if ($row['fechaBitacora'] == 0) {

                        $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%;"><center>Error al Obtener Fecha</center></td>';
                    } else {
                        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaBitacora'])));
                        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaBitacora'])));

                        $fechaBitacora2 = strtotime($row['fechaBitacora']);

                        $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%;"><center>' . $inicioFecha . '<br>' . $inicioHora . '</center></td>';
                    }

                    if ($row['idSubcategoria'] > 0) {
                        $nomSubcategoria = getNomSubCategoria($row["idSubcategoria"]);
                    } else {
                        $nomSubcategoria = 'Error al Obtener Categoria';
                    }

                    // $cadena .= '<td style="vertical-align:middle; width:60%;" align="justify">' . nl2br($row["observacionBitacora"]) . '</td>';

                    $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%;"><center>';

                    $cadena .= '<button type="button" data-toggle="modal" data-target="#modalObserva' . $row['idBitacora'] . '" id="inputMozi" class="btn btn-primary">Ver Observaci&oacute;n</button>';

                    $cadena .= '<div class="modal fade" id="modalObserva' . $row['idBitacora'] . '" name="modalObserva' . $row['idBitacora'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';

                    $cadena .= '<div class="modal-dialog" role="document">';

                    $cadena .= '<div class="modal-content">';

                    $cadena .= '<div class="modal-header bg-primary">';

                    $cadena .= '<h5 class="modal-title" id="exampleModalLabel">Visualizando Observaci&oacute;n</h5>';

                    $cadena .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';

                    $cadena .= '<span aria-hidden="true">&times;</span>';

                    $cadena .= '</button>';

                    $cadena .= '</div>';

                    $cadena .= '<div class="modal-body">';

                    $cadena .= '<div style="text-align:left;" class="form-group">';

                    $cadena .= '<label for="exampleInputBorder">Categoria: &nbsp;</label><label style="font-weight: normal;" for="exampleInputBorder">' . $nomSubcategoria . '</label><br>';

                    $cadena .= '<label for="exampleInputBorder">Tecnico: &nbsp;</label><label style="font-weight: normal;" for="exampleInputBorder">' . $row["tecnicoBitacora"] . '</label><br>';

                    $cadena .= '<label for="exampleInputBorder">Fecha Observacion: &nbsp;</label><label style="font-weight: normal;" for="exampleInputBorder">' . fechaEspañol($fechaBitacora2) . ' a las ' . $inicioHora . '</label><br>';

                    $cadena .= '<label for="exampleInputBorder">Observacion: &nbsp;</label><br>';

                    $cadena .= '<textarea id="observacionBitacora" style="resize:none;" readonly="readonly" name="observacionBitacora" class="form-control bg-light" rows="15" placeholder="Observaciones...">' . $row["observacionBitacora"] . '</textarea>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '<div class="modal-footer">';

                    $cadena .= '<button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</center></td>';



                    $cadena .= '<td nowrap="nowrap"  style="vertical-align:middle; width:10%;" align="center">' . $nomSubcategoria . '</td>';

                    $cadena .= '<td style="vertical-align:middle; width:10%;" align="center">' . $row['tecnicoBitacora'] . '</td>';

                    $cadena .= '<td nowrap="nowrap" style="vertical-align:middle; width:10%; align="center"><center>';

                    $cadena .= '<button type="button" data-toggle="modal" data-target="#modalModificar' . $row['idBitacora'] . '" id="inputMozi" class="btn btn-block btn-warning">Modificar Observaci&oacute;n</button>';

                    $cadena .= '<form method="post" action="../sdo-funciones/admin-modificarBitacora.php">';

                    $cadena .= '<div class="modal fade" id="modalModificar' . $row['idBitacora'] . '" name="modalModificar' . $row['idBitacora'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';

                    $cadena .= '<div class="modal-dialog" role="document">';

                    $cadena .= '<div class="modal-content">';

                    /// MODAL HEADER

                    $cadena .= '<div class="modal-header bg-primary">';

                    $cadena .= '<h5 class="modal-title" id="exampleModalLabel">Modificar Observaci&oacute;n</h5>';

                    $cadena .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';

                    $cadena .= '<span aria-hidden="true">&times;</span>';

                    $cadena .= '</button>';

                    $cadena .= '</div>';

                    /// FIN MODAL HEADER

                    /// MODAL BODY

                    $cadena .= '<div class="modal-body">';

                    $cadena .= '<div style="text-align:left;" class="form-group">';

                    $cadena .= '<div><label for="exampleInputDepartamento">Tecnico: ' . $row['tecnicoBitacora'] . '</label><br>';

                    $cadena .= '</div>';

                    $cadena .= '<div style="text-align:left;" class="form-group">';

                    $cadena .= '<div><label for="exampleInputDepartamento">Categoria:</label><br>';

                    $cadena .= '<select class="custom-select" name="subcategoriaModificar" id="subcategoriaModificar">';

                    $rowSubCategoria2 = $row['idSubcategoria'];

                    $idCategoria = obtenerIDBitacora();

                    $querySubcategoria = "SELECT * FROM subcategorias WHERE idCategoria = '" . $idCategoria . "' ORDER BY nomSubcategoria ASC";

                    $resSubcategoria = mysqli_query($conexion, $querySubcategoria);

                    while ($rowSubcategoria = $resSubcategoria->fetch_assoc()) {

                        if ($rowSubcategoria['idSubcategoria'] == $rowSubCategoria2) {

                            $cadena .= '<option value="' . $rowSubcategoria['idSubcategoria'] . '" selected="selected">' . $rowSubcategoria['nomSubcategoria'] . '</option>';
                        } else {
                            $cadena .= '<option value="' . $rowSubcategoria['idSubcategoria'] . '">' . $rowSubcategoria['nomSubcategoria'] . '</option>';
                        }
                    }

                    // $cadena .= '<option value="1">'.$querySubcategoria.'</option>';            

                    $cadena .= '</select>';

                    $cadena .= '</div><br>';

                    $cadena .= '<textarea id="modObservacionBitacora" style="resize:none;" name="modObservacionBitacora" class="form-control" rows="15" placeholder="Observaciones...">' . $row["observacionBitacora"] . '</textarea>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    /// FIN MODAL BODY

                    /// MODAL FOOTER

                    $cadena .= '<div class="modal-footer">';

                    $cadena .= '<input type="hidden" name="idBitacora" id="idBitacora" value="' . $row['idBitacora'] . '">';

                    $cadena .= '<button type="submit" class="btn btn-primary">Modificar</button>';

                    $cadena .= '<button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>';

                    $cadena .= '</div>';

                    /// FIN MODAL FOOTER

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '</form>';

                    $cadena .= '</center></td>';

                    $cadena .= '</tr>';

                    $numReporte++;
                }
            } else {
                $cadena .= '<tr>';
                $cadena .= '<td colspan="7"><center>No Existen Registros.</center></td>';
                $cadena .= '</tr>';
            }
        } else {
            $cadena .= '<tr>';
            $cadena .= '<td colspan="7"><center>No Existen Registros.</center></td>';
            $cadena .= '</tr>';
        }

        echo $cadena;
    }
