<form action="<?= $form_link?>" method="post"  >
    <?php include __DIR__ . '/../partials/element_parts/form_parts/name.php'; ?>
    <div class="form-group row mb-2">
        <label for="group" class="col-md-2 col-form-label" title="Diese Vorlage soll ausgefüllt werden.">
            Vorlage:
        </label>
        <div class="col-md-10">
            <select class="form-select" id="template" name="template" title="Diese Vorlage soll ausgefüllt werden.">
                <?php if (isset($object['template_id'])) { ?>
                    <?php foreach ($templates as $template) { ?>
                        <option <?= $template['id'] == $object['group_id'] ? "selected" : "" ?>
                            value="<?= $template['id'] ?>"><?= $template['name'] ?></option>
                    <?php } ?>
                <?php } else { ?>
                    <?php foreach ($templates as $template) { ?>
                        <option value="<?= $template['id'] ?>"><?= $template['name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <div class="mx-2 mt-1" style="color: var(--highlightColor)">
                Die Vorlage kann nachträglich nicht geändert werden.
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../partials/element_parts/form_parts/buttons.php'; ?>
</form>