<?php date_default_timezone_set('America/Santiago');?>
<?php $now = date('Y-m-d H:i:s')?>
<fieldset class="border p-3">
    <div class="row">
        <div class="form-row">
            <div class="form-group col-mb-2">
                <label style="float: left;">Fecha:</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                    </div>
                    <input type="datetime-local" class="form-control" id="fecha" name="fecha" value="<?php echo $now?>" readonly required>
                </div>
            </div>
            <div class="form-group col-mb-2">
                <label style="float: left;">Funcionario Asignado:</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="tecnico" name="tecnico" value="<?php echo $nomUsuario?>" required>
                </div>
            </div>
            <div class="form-group col-mb-2">
                <label style="float: left;">Turno:</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="turno" name="turno" value="<?php echo $turnoTG?>" required>
                </div>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-md-4">
                <table class="table-hover table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2">Parámetros Sistema Agua Caliente</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Caldera en servicio</td>
                        <td>
                            <input type="text" id="calderaServiciouno" name="calderaServiciouno">
                        </td>
                    </tr>
                    <tr>
                        <td>T° Operación (°C)</td>
                        <td>
                            <input type="text" id="tempoperacionuno" name="tempoperacionuno">
                        </td>
                    </tr>
                    <tr>
                        <td>Presión de Caldera (bar)</td>
                        <td>
                            <input type="text" id="presioncalderauno" name="presioncalderauno">
                        </td>
                    </tr>
                    <tr>
                        <td>Caldera en servicio</td>
                        <td><input type="text" id="calderaserviciodos" name="calderaserviciodos"></td>
                    </tr>
                    <tr>
                        <td>T° Operación (°C)</td>
                        <td>
                            <input type="text" id="tempoperaciondos" name="tempoperaciondos">
                        </td>
                    </tr>
                    <tr>
                        <td>Presión de Caldera (bar)</td>
                        <td>
                            <input type="text" id="presioncalderados" name="presioncalderados">
                        </td>
                    </tr>
                    <tr>
                        <td>Caldera en servicio</td>
                        <td><input type="text" id="calderaserviciotres" name="calderaserviciotres"></td>
                    </tr>
                    <tr>
                        <td>T° Operación (°C)</td>
                        <td>
                            <input type="text" id="tempoperaciontres" name="tempoperaciontres">
                        </td>
                    </tr>
                    <tr>
                        <td>Presión de Caldera (bar)</td>
                        <td>
                            <input type="text" id="presioncalderatres" name="presioncalderatres">
                        </td>
                    </tr>
                    <tr>
                        <td>Caldera en servicio</td>
                        <td><input type="text" id="calderaserviciocuatro" name="calderaserviciocuatro"></td>
                    </tr>
                    <tr>
                        <td>T° Operación (°C)</td>
                        <td>
                            <input type="text" id="tempoperacioncuatro" name="tempoperacioncuatro">
                        </td>
                    </tr>
                    <tr>
                        <td>Presión de Caldera (bar)</td>
                        <td>
                            <input type="text" id="presioncalderacuatro" name="presioncalderacuatro">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table-hover table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2">BOMBA CENTRÍFUGA AGUA CALIENTE PRIMARIA</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPUNOPRIMARIA" name="BCAPUNOPRIMARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapunoPRIMARIA" name="presionbcapunoPRIMARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPDOSPRIMARIA" name="BCAPDOSPRIMARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapDOSPRIMARIA" name="presionbcapDOSPRIMARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPTRESPRIMARIA" name="BCAPTRESPRIMARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapTRESPRIMARIA" name="presionbcapTRESPRIMARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPCUATROPRIMARIA" name="BCAPCUATROPRIMARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapCUATROPRIMARIA" name="presionbcapCUATROPRIMARIA">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table-hover table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2">BOMBA CENTRÍFUGA AGUA CALIENTE SECUNDARIA</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPUNOSECUNDARIA" name="BCAPUNOSECUNDARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapunoSECUNDARIA" name="presionbcapunoSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>RPM</td>
                            <td>
                                <input type="text" id="RPMunoSECUNDARIA" name="RPMunoSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPDOSSECUNDARIA" name="BCAPDOSSECUNDARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapDOSSECUNDARIA" name="presionbcapDOSSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>RPM</td>
                            <td>
                                <input type="text" id="RPMDOSSECUNDARIA" name="RPMDOSSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPtresSECUNDARIA" name="BCAPtresSECUNDARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcaptresSECUNDARIA" name="presionbcaptresSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>RPM</td>
                            <td>
                                <input type="text" id="RPMtresSECUNDARIA" name="RPMtresSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPcuatroSECUNDARIA" name="BCAPcuatroSECUNDARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapcuatroSECUNDARIA" name="presionbcapcuatroSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>RPM</td>
                            <td>
                                <input type="text" id="RPMcuatroSECUNDARIA" name="RPMcuatroSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>BCAP</td>
                            <td>
                                <input type="text" id="BCAPcincoSECUNDARIA" name="BCAPcincoSECUNDARIA" width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td>
                                <input type="text" id="presionbcapcincoSECUNDARIA" name="presionbcapcincoSECUNDARIA">
                            </td>
                        </tr>
                        <tr>
                            <td>RPM</td>
                            <td>
                                <input type="text" id="RPMcincoSECUNDARIA" name="RPMcincoSECUNDARIA">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th colspan="2">BOMBA CENTRÍFUGA RECUPERACIÓN DE CALOR ACS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>BCC</td>
                            <td><input type="text" id="bccuno" name="bccuno"></td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td><input type="text" id="bccuno" name="bccuno"></td>
                        </tr>
                        <tr>
                            <td>BCC</td>
                            <td><input type="text" id="bccdos" name="bccdos"></td>
                        </tr>
                        <tr>
                            <td>Presion (bar)</td>
                            <td><input type="text" id="bccdos" name="bccdos "></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>