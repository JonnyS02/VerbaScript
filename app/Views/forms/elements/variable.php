<div class="form-floating" title="<?= $element['info'] ?>">
    <input type="hidden" name="elements[element<?= $element['id'] ?>][id]" value="<?= $element['id'] ?>">
    <input data-ai-label="<?= $element['name'] ?>" type="<?= $element['type'] ?>" class="form-control"
           id="variable<?= $element['id'] ?>"
           placeholder="" name="elements[element<?= $element['id'] ?>][value]" value="<?= $element['value'] ?>"
        <?= $element['required'] ? "required" : "" ?>>
    <label for="variable<?= $element['id'] ?>"><?= $element['name'] . ($element['required'] ? "*" : "") ?>  </label>
</div>