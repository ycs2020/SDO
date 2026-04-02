<td nowrap="nowrap" style="vertical-align:middle; width:10%;">
    <center>

        <button type="button" data-toggle="modal" data-target="#modalModificar" id="inputMozi" class="btn btn-warning">Modificar Observaci&oacute;n</button>

        <form method="post" action="../sdo-funciones/tecnico-modificarBitacora.php">

            <div class="modal fade" id="modalModificar" name="modalModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                <div class="modal-dialog" role="document">

                    <div class="modal-content">


                        <div class="modal-header bg-primary">

                            <h5 class="modal-title" id="exampleModalLabel">Modificar Observaci&oacute;n</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>

                        <div class="modal-body">

                            <div style="text-align:left;" class="form-group">

                                <div><label for="exampleInputDepartamento">Tecnico: '.$row['tecnicoBitacora'].'</label><br>

                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>

                        </div>


                    </div>

                </div>

            </div>

        </form>

    </center>
</td>