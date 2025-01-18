<form action="<?= $form_link ?>" method="post">
    <?php
    include __DIR__ . '/../partials/element_parts/form_parts/name.php';
    include __DIR__ . '/../partials/element_parts/form_parts/group.php';
    include __DIR__ . '/../partials/element_parts/form_parts/info.php';
    include __DIR__ . '/../partials/element_parts/form_parts/ai_info.php';
    ?>
    <div id="optionsContainer" onkeyup="checkOptionsValidity()">
        <div class="form-group row mt-3" id="fistOption">
            <label for="name-option1" class="col-md-2 col-form-label">Option 1:</label>
            <div class="col-md-10">
                <input value="<?= $object['options'][0]['name'] ?? "" ?>" required
                       class="form-control border-radius-top"
                       type="text" placeholder="Optionsname" title="Optionsname" id="name-option1"
                       name="options[option-1][name]">
                <textarea aria-label="Optionsinhalt" class="form-control border-radius-bottom" id="option1"
                          name="options[option-1][content]" rows="3"
                          placeholder="Optionsinhalt" title="Optionsinhalt"
                          required><?= $object['options'][0]['content'] ?? "" ?></textarea>
            </div>
        </div>
        <?php if (isset($object['options']) && is_array($object['options'])) {
            for ($i = 1; $i < count($object['options']); $i++) { ?>
                <div class="form-group row mt-3">
                    <label for="name-option<?= $i + 1 ?>" class="col-md-2 col-form-label">Option <?= $i + 1 ?>:</label>
                    <div class="col-md-10">
                        <input value="<?= $object['options'][$i]['name'] ?? "" ?>"
                               class="form-control border-radius-top"
                               type="text" placeholder="Optionsname" title="Optionsname"
                               id="name-option<?= $i + 1 ?>" name="options[option-<?= $i + 1 ?>][name]">
                        <textarea aria-label="Optionsinhalt" class="form-control border-radius-bottom"
                                  id="option<?= $i + 1 ?>"
                                  name="options[option-<?= $i + 1 ?>][content]" rows="3" title="Optionsinhalt"
                                  placeholder="Optionsinhalt"><?= $object['options'][$i]['content'] ?? "" ?></textarea>
                    </div>
                </div>
            <?php }
        } ?>
    </div>
    <div class="form-group row mb-4 mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <i class="fa-solid fa-square-plus smooth-transition" title="Option hinzufügen"
               onclick="addOption()"></i>
            <i class="fa-solid fa-square-minus smooth-transition" title="Letzte Option entfernen"
               onclick="removeOption()"></i>
        </div>
    </div>
    <div class="form-group row mb-2">
        <label for="standard_option" class="col-md-2 col-form-label">Standard:</label>
        <div class="col-md-10">
            <input value="<?= (isset($object['additional']['standard_option']) && $object['additional']['standard_option'] > 0) ? $object['additional']['standard_option'] : "" ?>"
                   type="number" min="0" max="<?= isset($object['options']) ? count($object['options']) : 1 ?>" step="1"
                   class="form-control" id="standard_option" name="standard_option" autocomplete="off"
                   placeholder="Nummer der Standard Option"
                   title="Füllen Sie dieses Feld aus, wenn eine bestimmte Option standardmäßig als erstes ausgewählt werden soll.">
        </div>
    </div>
    <div class="form-group row mb-1">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="form-check form-switch">
                <label class="form-check-label" for="allow_individual">Individueller Text erlaubt</label>
                <input class="form-check-input" type="checkbox" id="allow_individual"
                       name="allow_individual" <?= (isset($object['additional']['allow_individual']) && $object['additional']['allow_individual']) ? "checked" : "" ?>>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . '/../partials/element_parts/form_parts/omitted_required.php';
    include __DIR__ . '/../partials/element_parts/form_parts/buttons.php';
    ?>
</form>
<script>
    function addOption() {
        const $optionsContainer = $('#optionsContainer');
        const optionsCount = $optionsContainer.find('textarea').length;
        const $newOptionDiv = $('<div>').addClass('form-group row mt-3').css('opacity', '0');
        const $newNameInput = $('<input>').addClass('form-control border-radius-top').attr({
            type: 'text',
            placeholder: 'Optionsname',
            id: 'name-option' + (optionsCount + 1),
            name: 'options[option-' + (optionsCount + 1) + '][name]',
            title: 'Optionsname'
        });
        const $newLabel = $('<label>').addClass('col-md-2 col-form-label').text('Option ' + (optionsCount + 1) + ':').attr('for', 'name-option' + (optionsCount + 1));
        const $newInputDiv = $('<div>').addClass('col-md-10');
        const $newTextarea = $('<textarea>').addClass('form-control border-radius-bottom').attr({
            id: 'option' + (optionsCount + 1),
            name: 'options[option-' + (optionsCount + 1) + '][content]',
            rows: 3,
            placeholder: 'Optionsinhalt',
            title: 'Optionsinhalt'
        });
        $newInputDiv.append($newNameInput, $newTextarea);
        $newOptionDiv.append($newLabel, $newInputDiv);
        $optionsContainer.append($newOptionDiv);

        setTimeout(function () {
            $newOptionDiv.css({
                transition: 'opacity 0.25s ease-in',
                opacity: '1'
            });
        }, 5);
        updateStandardOption();
    }

    function updateStandardOption() {
        const optionsCount = $('#optionsContainer').find('textarea').length;
        $('#standard_option').attr('max', optionsCount);
    }

    function removeOption() {
        const $optionsContainer = $('#optionsContainer');
        const $lastOption = $optionsContainer.children().last();
        if ($lastOption.length && $lastOption.attr('id') !== 'fistOption') {
            $lastOption.css({
                transition: 'opacity 0.25s ease-out',
                opacity: '0'
            });
            setTimeout(function () {
                $lastOption.remove();
            }, 250);
        }
        updateStandardOption();
    }

    function checkOptionsValidity() {
        $('#optionsContainer .form-group').each(function (index) {
            const $option = $(this);
            const $optionContentElement = $option.find('textarea');
            const $optionNameElement = $option.find('input[type="text"]');
            const optionContent = $optionContentElement.val().trim();
            const optionName = $optionNameElement.val().trim();
            if ((optionContent === '' || optionName === '') && !(optionContent === '' && optionName === '')) {
                if (optionContent === '') {
                    $optionContentElement.prop('required', true);
                }
                if (optionName === '') {
                    $optionNameElement.prop('required', true);
                }
            } else {
                if (index !== 0) {
                    $optionContentElement.prop('required', false);
                    $optionNameElement.prop('required', false);
                }
            }
        });
    }
</script>