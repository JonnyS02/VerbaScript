<form action="<?= $form_link ?>" method="post">
    <?php
    include __DIR__ . '/../partials/element_parts/form_parts/name.php';
    include __DIR__ . '/../partials/element_parts/form_parts/group.php';
    include __DIR__ . '/../partials/element_parts/form_parts/info.php';
    include __DIR__ . '/../partials/element_parts/form_parts/ai_info.php';
    ?>
    <div class="form-group row mb-2">
        <label for="input_type" class="col-md-2 col-form-label"
               title="Elemente können gruppiert werden, um sie leichter zu finden.">Feld-Typ:
        </label>
        <div class="col-md-10">
            <select class="form-select" id="input_type" name="input_type"
                    title="Legt fest, welchen Typ das Feld haben soll.">
                <?php if (isset($object['input_type_id'])) { ?>
                    <?php foreach ($types as $type) { ?>
                        <option title="<?= $type['description'] ?>" <?= $type['id'] == $object['input_type_id'] ? "selected" : "" ?>
                                value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                    <?php } ?>
                <?php } else { ?>
                    <?php foreach ($types as $type) { ?>
                        <option title="<?= $type['description'] ?>" value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <?php
    include __DIR__ . '/../partials/element_parts/form_parts/omitted_required.php';
    include __DIR__ . '/../partials/element_parts/form_parts/buttons.php';
    ?>
</form>
