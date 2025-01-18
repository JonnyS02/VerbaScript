<div class="form-group row mb-2">
    <label for="group" class="col-md-2 col-form-label"
           title="Elemente können gruppiert werden, um sie leichter zu finden.">
        Gruppe:
    </label>
    <div class="col-md-10">
        <select class="form-select" id="group" name="group"
                title="Elemente können gruppiert werden, um sie leichter zu finden.">
            <?php if (isset($object['group_id'])) { ?>
                <option value="">Gruppe</option>
                <?php foreach ($groups as $group) { ?>
                    <option <?= $group['id'] == $object['group_id'] ? "selected" : "" ?>
                            value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                <?php } ?>
            <?php } else { ?>
                <option selected value="">Gruppe</option>
                <?php foreach ($groups as $group) { ?>
                    <option value="<?= $group['id'] ?>"><?= $group['name'] ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
</div>

