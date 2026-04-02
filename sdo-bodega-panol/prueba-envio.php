<form action="../sdo-funciones/enviar-archivo.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Subir Orden de Compra</label>
        <input type="file" class="form-control form-control-sm" id="archivoAdjunto" name="archivoAdjunto[]" multiple aria-describedby="emailHelp">
        <small id="emailHelp" class="form-text text-muted">Suba el archivo de la orden de compra</small>
    </div>
    <br>
    <button type="submit">Subir</button>
</form>