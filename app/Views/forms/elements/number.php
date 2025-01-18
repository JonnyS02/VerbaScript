<div class="form-floating" title="<?= $element['info'] ?>">
    <input type="hidden" name="elements[element<?= $element['id'] ?>][id]" value="<?= $element['id'] ?>">
    <input data-ai-label="<?= $element['name'] ?>" type="number" class="form-control" id="number<?= $element['id'] ?>"
           placeholder="" name="elements[element<?= $element['id'] ?>][value]" step="0.01"
           value="<?= $element['value'] ?>"
        <?= $element['required'] ? "required" : "" ?>>
    <label for="number<?= $element['id'] ?>"><?= $element['name'] . ($element['required'] ? "*" : "") ?></label>
</div>