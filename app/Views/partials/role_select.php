<div class="form-group row mb-2">
    <label for="role" class="col-md-2 col-form-label" title="Legen Sie die berechtigung des Nutzers fest.">
        Rolle:
    </label>
    <div class="col-md-10">
        <select class="form-select" id="role" name="role" title="<?= isset($disable_role_select) ? 'Diese Rolle wird die Person bekommen.' : 'Legen Sie die Berechtigung der Person fest.' ?>" <?= isset($disable_role_select) ? 'disabled' : '' ?>>
            <?php foreach ($roles as $role) { ?>
                <option value="<?= $role['id'] ?>" <?= isset($object['role_id']) && $object['role_id'] == $role['id'] ? "selected" : "" ?>>
                    <?= $role['name'] ?>
                </option>
            <?php } ?>
        </select>
    </div>
</div>