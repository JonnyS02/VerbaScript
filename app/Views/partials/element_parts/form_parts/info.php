<div class="form-group row mb-2">
    <label for="info" class="col-md-2 col-form-label ">Information:</label>
    <div class="col-md-10">
        <input type="text" class="form-control" id="info" placeholder="Hilfreiche Information"
               name="info" title="Diese Information wird zusätzlich angezeigt, wenn man über das Feld im Formular fährt."
               value="<?= $object['info'] ?? "" ?>">
    </div>
</div>