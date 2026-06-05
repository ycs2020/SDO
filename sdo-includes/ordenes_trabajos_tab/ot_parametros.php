<div class="card card-outline card-success shadow-sm">
    <div class="card-body p-2">
        <div class="table-responsive">
            <table id="example2" class="table table-bordered table-striped table-hover table-sm w-100" style="font-size: 13px;">
                <thead class="bg-secondary text-white text-center">
                <tr>
                    <th style="vertical-align:middle;">Fecha</th>
                    <th style="vertical-align:middle;">Funcionario</th>
                    <?php for ($i = 2; $i <= 10; $i++) { echo "<th style='vertical-align:middle;'>Pabellón $i</th>"; } ?>
                    <th>Enviar</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>