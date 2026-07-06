<?php
// Asegurar que las variables críticas existan (por si el archivo se incluye directamente)
$idTG = $idTG ?? $_SESSION['idTG'] ?? null;
$nomUsuario = $nomUsuario ?? $_SESSION['nomUsuario'] ?? null;

$verificarVisadosTG = verificarVisadosTG($idTG);


?>
<input type="hidden" id="idTGVisador" value="<?php echo (int)$idTG; ?>">
<input type="hidden" id="nomUsuarioVisador" value="<?php echo htmlspecialchars((string)$nomUsuario, ENT_QUOTES, 'UTF-8'); ?>">

<input type="hidden" id="usuarioCarga" value="<?php echo htmlspecialchars((string)$nomUsuario, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="folioCarga" value="<?php echo htmlspecialchars((string)$folioTG, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="idCarga" value="<?php echo (int)$idTG; ?>">

<div class="p-2">
    <?php if ($verificarVisadosTG === 1 && $estadoTG === 'Trabajo Terminado') { ?>
        <div class="row text-center mb-3" style="display: flex; justify-content: center;">
            <div class="col-12 col-md-4 col-lg-3">
                <button type="button" id="btnVisarTodoTG" class="btn btn-success btn-block shadow-sm">
                    <i class="fas fa-check-double mr-2"></i>Visar Documentación Total
                </button>
                <input type="hidden" id="idTGVisador" value="<?php echo (int)$idTG; ?>">
                <input type="hidden" id="nomUsuarioVisador" value="<?php echo htmlspecialchars((string)$nomUsuario, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>
        <hr>
    <?php } ?>

    <ul class="nav nav-tabs mt-2" id="tabsInternosDocumentacion" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tab-ht-tab" data-toggle="tab" href="#tabHT" role="tab" aria-controls="tabHT" aria-selected="true">
                <i class="fas fa-file-medical-alt mr-1 text-info"></i> Hojas Técnicas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-ot-tab" data-toggle="tab" href="#tabOT" role="tab" aria-controls="tabOT" aria-selected="false">
                <i class="fas fa-tools mr-1 text-warning"></i> Órdenes de Trabajo
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-check-tab" data-toggle="tab" href="#tabCheck" role="tab" aria-controls="tabCheck" aria-selected="false">
                <i class="fas fa-clipboard-check mr-1 text-success"></i> Check-List
            </a>
        </li>
    </ul>

    <div class="tab-content pt-3" id="tabsInternosDocumentacionContent">

        <div class="tab-pane fade show active" id="tabHT" role="tabpanel" aria-labelledby="tab-ht-tab">
            <div class="table-responsive">
                <table id="tableHTVisar" class="table table-bordered table-striped table-sm text-center w-100">
                    <thead>
                    <tr class="bg-dark text-white">
                        <th colspan="8" class="py-2">REGISTRO DE HOJAS TÉCNICAS ASOCIADAS</th>
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th>N°</th>
                        <th>Ticket Asociado</th>
                        <th>Folio HT</th>
                        <th>Personal HT</th>
                        <th>Solicitante HT</th>
                        <th>Fecha HT</th>
                        <th>Ver HT</th>
                        <th>Visar HT</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr class="bg-light text-muted">
                        <th>N°</th>
                        <th>Ticket</th>
                        <th>Folio</th>
                        <th>Personal</th>
                        <th>Solicitante</th>
                        <th>Fecha</th>
                        <th>Ver</th>
                        <th>Visar</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="tabOT" role="tabpanel" aria-labelledby="tab-ot-tab">
            <div class="table-responsive">
                <table id="tableOTVisar" class="table table-bordered table-striped table-sm text-center w-100">
                    <thead>
                    <tr class="bg-dark text-white">
                        <th colspan="9" class="py-2">REGISTRO DE ÓRDENES DE TRABAJO RELACIONADAS</th>
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th>N°</th>
                        <th>Folio OT</th>
                        <th>Personal OT</th>
                        <th>Solicitante OT</th>
                        <th>Fecha OT</th>
                        <th>Turno OT</th>
                        <th>Estado OT</th>
                        <th>Ver OT</th>
                        <th>Visar OT</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr class="bg-light text-muted">
                        <th>N°</th>
                        <th>Folio</th>
                        <th>Personal</th>
                        <th>Solicitante</th>
                        <th>Fecha</th>
                        <th>Turno</th>
                        <th>Estado</th>
                        <th>Ver</th>
                        <th>Visar</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="tabCheck" role="tabpanel" aria-labelledby="tab-check-tab">
            <div class="table-responsive">
                <table id="tableCheckVisar" class="table table-bordered table-striped table-sm text-center w-100">
                    <thead>
                    <tr class="bg-dark text-white">
                        <th colspan="7" class="py-2">CHECKLISTS DE INSPECCIÓN OPERACIONAL</th>
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th>N°</th>
                        <th>Folio CheckList</th>
                        <th>Tipo CheckList</th>
                        <th>Equipo CheckList</th>
                        <th>Fecha CheckList</th>
                        <th>Ver CheckList</th>
                        <th>Visar CheckList</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr class="bg-light text-muted">
                        <th>N°</th>
                        <th>Folio</th>
                        <th>Tipo</th>
                        <th>Equipo</th>
                        <th>Fecha</th>
                        <th>Ver</th>
                        <th>Visar</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>