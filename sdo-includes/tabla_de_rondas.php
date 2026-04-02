<center>
    <?php date_default_timezone_set('America/Santiago');?>
    <?php $now = date('H:i:s')?>
    <fieldset class="border p-3">
        <legend class="w-auto">Tabla de Ronda de Inspeccion</legend>
        <div class="row">
            <div class="form-row">
                <div class="form-group col-mb-2">
                    <label for="exampleInputQuienSolicita" style="float: left;">Horario de la Ronda:</label> <code>&nbsp;Recuerde ocupar formato de 24hrs (0:00 am hasta las 23:59 pm)</code>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        </div>
                        <input type="text" style="text-transform:capitalize" class="form-control" id="horario"
                               name="horario">
                    </div>
                </div>
            </div>
            <div class="form-group col-mb-2">
                <label class="form-check-label" style="float: right;"> Marcar Todos</label>
                <br>
                <input type="checkbox" style="float: right;" class="show-mostrar" onchange="marcarOtroInput()">
            </div>
            <div class="col-sm">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"  value="1" name="torre1" id="torre1">
                    <label class="form-check-label">
                        Torre 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="torre2" id="torre2">
                    <label class="form-check-label">
                        Torre 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="torre3" id="torre3">
                    <label class="form-check-label">
                        Torre 3
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="torre4" id="torre4">
                    <label class="form-check-label">
                        Torre 4
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="chiller1" id="chiller1">
                    <label class="form-check-label">
                        &nbsp;Chiller 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="chiller2" id="chiller2">
                    <label class="form-check-label">
                        &nbsp;Chiller 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="chiller3" id="chiller3">
                    <label class="form-check-label">
                        Chiller 3
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="chiller4" id="chiller4">
                    <label class="form-check-label">
                        Chiller 4
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bac1" id="bac1">
                    <label class="form-check-label">
                        BAC 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bac2" id="bac2">
                    <label class="form-check-label">
                        BAC 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bac3R" id="bac3R">
                    <label class="form-check-label">
                        BAC 3R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bac4" id="bac4">
                    <label class="form-check-label">
                        BAC 4
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bac4R" id="bac4R">
                    <label class="form-check-label">
                        BAC 4R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="barvf2" id="barvf2">
                    <label class="form-check-label">
                        BARVF 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baep1" id="baep1">
                    <label class="form-check-label">
                        BAEP 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baep2" id="baep2">
                    <label class="form-check-label">
                        BAEP 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baep3R" id="baep3R">
                    <label class="form-check-label">
                        BAEP 3R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baep4" id="baep4">
                    <label class="form-check-label">
                        BAEP 4
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baep4R" id="baep4R">
                    <label class="form-check-label">
                        BAEP 4R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes1" id="baes1">
                    <label class="form-check-label">
                        BAES 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes1R" id="baes1R">
                    <label class="form-check-label">
                        BAES 1R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes2" id="baes2">
                    <label class="form-check-label">
                        BAES 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes2R" id="baes2R">
                    <label class="form-check-label">
                        BAES 2R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes2_1" id="baes2_1">
                    <label class="form-check-label">
                        BAES 2.1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes3" id="baes3">
                    <label class="form-check-label">
                        BAES 3
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes3R" id="baes3R">
                    <label class="form-check-label">
                        BAES 3R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes4" id="baes4">
                    <label class="form-check-label">
                        BAES 4
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="baes4R" id="baes4R">
                    <label class="form-check-label">
                        BAES 4R
                    </label>
                </div>
            </div>

            <div class="col-sm">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="caldera1" id="caldera1">
                    <label class="form-check-label">
                        Caldera 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="caldera2" id="caldera2">
                    <label class="form-check-label">
                        Caldera 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="caldera3" id="caldera3">
                    <label class="form-check-label">
                        Caldera 3
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="caldera4" id="caldera4">
                    <label class="form-check-label">
                        Caldera 4
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="caldera5" id="caldera5">
                    <label class="form-check-label">
                        Caldera 5
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcap5" id="bcap5">
                    <label class="form-check-label">
                        BCAP 5
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcap5R" id="bcap5R">
                    <label class="form-check-label">
                        BCAP 5R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcap1" id="bcap1">
                    <label class="form-check-label">
                        BCAP 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcap2" id="bcap2">
                    <label class="form-check-label">
                        BCAP 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcap3" id="bcap3">
                    <label class="form-check-label">
                        BCAP 3
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcap4R" id="bcap4R">
                    <label class="form-check-label">
                        BCAP 4R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas1" id="bcas1">
                    <label class="form-check-label">
                        BCAS 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas1R" id="bcas1R">
                    <label class="form-check-label">
                        BCAS 1R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas2" id="bcas2">
                    <label class="form-check-label">
                        BCAS 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas2R" id="bcas2R">
                    <label class="form-check-label">
                        BCAS 2R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas2_1" id="bcas2_1">
                    <label class="form-check-label">
                        BCAS 2.1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas3" id="bcas3">
                    <label class="form-check-label">
                        BCAS 3
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas3R" id="bcas3R">
                    <label class="form-check-label">
                        BCAS 3R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas4" id="bcas4">
                    <label class="form-check-label">
                        BCAS 4
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcas4R" id="bcas4R">
                    <label class="form-check-label">
                        BCAS 4R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bracs1" id="bracs1">
                    <label class="form-check-label">
                        BRACS 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bracs1R" id="bracs1R">
                    <label class="form-check-label">
                        BRACS 1R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bracs2" id="bracs2">
                    <label class="form-check-label">
                        BRACS 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bracs2R" id="bracs2R">
                    <label class="form-check-label">
                        BRACS 2R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcc1" id="bcc1">
                    <label class="form-check-label">
                        BCC 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcc1R" id="bcc1R">
                    <label class="form-check-label">
                        BCC 1R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcc2" id="bcc2">
                    <label class="form-check-label">
                        BCC 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcc2R" id="bcc2R">
                    <label class="form-check-label">
                        BCC 2R
                    </label>
                </div>
            </div>

            <div class="col-sm">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="bcpss1" id="bcpss1">
                    <label class="form-check-label">
                        BCPSS 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcpss1R" id="bcpss1R">
                    <label class="form-check-label">
                        BCPSS 1R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcpss2" id="bcpss2">
                    <label class="form-check-label">
                        BCPSS 2
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bcpss2R" id="bcpss2R">
                    <label class="form-check-label">
                        BCPSS 2R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bsap1" id="bsap1">
                    <label class="form-check-label">
                        BSAP 1
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="bsap1R" id="bsap1R">
                    <label class="form-check-label">
                        BSAP 1R
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_264" id="valv_264">
                    <label class="form-check-label">
                        valv_264
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_262" id="valv_262">
                    <label class="form-check-label">
                        valv_262
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_260" id="valv_260">
                    <label class="form-check-label">
                        valv_260
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_261" id="valv_261">
                    <label class="form-check-label">
                        valv_261
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_208" id="valv_208">
                    <label class="form-check-label">
                        valv_208
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_205" id="valv_205">
                    <label class="form-check-label">
                        valv_205
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_263" id="valv_263">
                    <label class="form-check-label">
                        valv_263
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="valv_265" id="valv_265">
                    <label class="form-check-label">
                        valv_265
                    </label>
                    <br>
                    <input class="form-check-input" type="checkbox" value="1" name="drycooler" id="drycooler">
                    <label class="form-check-label">
                        Dry-cooler
                    </label>
                </div>
            </div>
    </fieldset>
    <br>
    <fieldset class="border p-3">
        <legend class="w-auto">Revisión de Temperatura - Temperatura en Recinto [°C]</legend>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">VDRL (Rango Normal:
                    19-22°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="vdrl" name="vdrl">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">IFI/ C-2-9 (Rango Normal: 18-22°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="ifi" name="ifi"
                           value="">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">UCI Adulto (Rango Normal: 22-24°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="uci" name="uci"
                           value="">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">UTI Pediatrica (Rango Normal: 22-24°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="uti" name="uti"
                           value="">
                </div>
            </div>
        </div>
    </fieldset>
    <br>
    <fieldset class="border p-3">
        <legend class="w-auto">Revisión de Temperatura - Temperatura en BMS [°C]</legend>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">VDRL (Rango Normal:
                    19-22°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="vdrlBMS" name="vdrlBMS">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">IFI/ C-2-9 (Rango Normal: 18-22°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="ifiBMS" name="ifiBMS"
                           value="">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">UCI Adulto (Rango Normal: 22-24°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="uciBMS" name="uciBMS"
                           value="">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputQuienSolicita" style="float: left;">UTI Pediatrica (Rango Normal: 22-24°C):</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                    </div>
                    <input type="text" style="text-transform:capitalize" class="form-control" id="utiBMS" name="utiBMS"
                           value="">
                </div>
            </div>
        </div>
    </fieldset>

</center>