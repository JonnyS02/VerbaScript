<div class="form-floating" title="<?= $element['info'] ?>">
    <input type="hidden" name="elements[element<?= $element['id'] ?>][id]" value="<?= $element['id'] ?>">
    <select data-ai-label="<?= $element['name'] ?>" class="form-select" id="select<?= $element['id'] ?>"
            name="elements[element<?= $element['id'] ?>][value]"
            aria-label="Floating label select example" <?= $element['required'] ? "required" : "" ?>>
        <option value="">Keine Option ausgewählt</option>
        <?php foreach ($element['options'] as $index => $option) : ?>
            <?php if (!empty($element['value']) && !$element['custom_select']) { ?>
                <option title="<?= $option['content'] ?>"
                        value="<?= $index + 1 ?>" <?= $element['value'] == $index + 1 ? "selected" : "" ?>><?= $option['name'] ?></option>
            <?php } else { ?>
                <option title="<?= $option['content'] ?>"
                        value="<?= $index + 1 ?>" <?= ($element['standard_option'] - 1) == $index ? "selected" : "" ?>><?= $option['name'] ?></option>
            <?php } ?>
        <?php endforeach; ?>
        <?php if ($element['allow_individual']) { ?>
            <option value="custom" <?= $element['custom_select'] ? "selected" : "" ?>>Individueller Text</option>
        <?php } ?>
    </select>
    <label for="select<?= $element['id'] ?>"><?= $element['name'] . ($element['required'] ? "*" : "") ?></label>
</div>
<?php if ($element['allow_individual']) { ?>
    <div class="<?= $element['custom_select'] ? '' : 'd-none' ?>" id="customInput<?= $element['id'] ?>">
        <textarea aria-label="textareaCustomInput<?= $element['id'] ?>" class="form-control border-radius-bottom"
                  name="elements[element<?= $element['id'] ?>][custom-value]"
                  <?= ($element['required'] && $element['custom_select']) ? "required" : "" ?>
                  placeholder="Individueller Text"
                  id="textareaCustomInput<?= $element['id'] ?>"><?= $element['custom_select'] ? htmlspecialchars($element['value']) : '' ?></textarea>
    </div>
    <script>
        $(document).ready(function () {
            const selectElement = $('#select<?= $element['id'] ?>');
            const customInputElement = $('#customInput<?= $element['id'] ?>');
            const textareaElement = $('#textareaCustomInput<?= $element['id'] ?>');
            selectElement.change(function () {
                if ($(this).val() === 'custom') {
                    customInputElement.removeClass('d-none');
                    selectElement.addClass('border-radius-top');
                    if (selectElement.attr('required')) {
                        textareaElement.attr('required', 'required');
                    }
                } else {
                    customInputElement.addClass('d-none');
                    selectElement.removeClass('border-radius-top');
                    textareaElement.removeAttr('required');
                }
            });
            if (<?= $element['custom_select'] ? 'true' : 'false' ?>) {
                customInputElement.removeClass('d-none');
                selectElement.addClass('border-radius-top');
                if (selectElement.attr('required')) {
                    textareaElement.attr('required', 'required');
                }
            }
        });
    </script>
<?php } ?>
