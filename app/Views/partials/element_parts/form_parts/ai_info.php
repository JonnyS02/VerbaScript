<div class="form-group row mb-2">
    <label for="ai_info" class="col-md-2 col-form-label ">KI-Input:</label>
    <div class="col-md-10">
        <input type="text" class="form-control" id="ai_info" placeholder="Hilfreiche Information für die KI"
               name="ai_info" title="Informationen für die KI, um das Feld besser interpretieren zu können."
               value="<?= $object['ai_info'] ?? "" ?>">
    </div>
</div>